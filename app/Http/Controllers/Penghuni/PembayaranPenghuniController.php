<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Transaction;

class PembayaranPenghuniController extends Controller
{
    private function checkPenghuniAktif()
    {
        return \App\Models\Penghuni::where('user_id', auth()->id())
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->exists();
    }

    public function viewPembayaran()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        $userId   = Auth::id();
        $payments = Pembayaran::where('user_id', $userId)
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        // Ambil cicilan paling baru
        $cicilan1 = $payments
            ->filter(fn($p) => str_contains($p->id_pembayaran, '-c1-'))
            ->sortByDesc('id')
            ->first();

        $cicilan2 = $payments
            ->filter(fn($p) => str_contains($p->id_pembayaran, '-c2-'))
            ->sortByDesc('id')
            ->first();

        // Induk yang sudah dipecah jadi cicilan
        $indukDenganCicilan = $payments
            ->filter(fn($p) => str_contains($p->id_pembayaran, '-c1-') || str_contains($p->id_pembayaran, '-c2-'))
            ->map(fn($p) => preg_replace('/-c[12]-\d+$/', '', $p->id_pembayaran))
            ->unique()->values()->toArray();

        // Pending = tagihan belum_bayar yang bukan pecahan cicilan dan bukan induk yang sudah dipecah
        $pending = $payments
            ->where('status', 'belum_bayar')
            ->filter(fn($p) =>
                !str_contains($p->id_pembayaran, '-c1-') &&
                !str_contains($p->id_pembayaran, '-c2-') &&
                !in_array($p->id_pembayaran, $indukDenganCicilan)
            )->first();

        // Tentukan apakah ada tagihan sama sekali
        $adaTagihan = $payments->isNotEmpty();

        // Tentukan sudahLunas
        if (!$adaTagihan) {
            // Tidak ada record sama sekali → belum ada tagihan
            $sudahLunas = false;
        } elseif ($cicilan1 || $cicilan2) {
            // Mode cicilan: lunas kalau KEDUA cicilan sudah lunas
            $sudahLunas = ($cicilan1 && $cicilan1->status === 'lunas') &&
                          ($cicilan2 && $cicilan2->status === 'lunas');
        } else {
            // Mode lunas biasa: ada tagihan tapi tidak ada pending = sudah lunas
            $sudahLunas = $adaTagihan && !$pending;
        }

        // History: semua yang lunas, kecuali induk yang sudah dipecah jadi cicilan
        $historyQuery = Pembayaran::where('user_id', $userId)
            ->where('status', 'lunas');

        if (!empty($indukDenganCicilan)) {
            $historyQuery->whereNotIn('id_pembayaran', $indukDenganCicilan);
        }

        $history = $historyQuery->orderByDesc('paid_at')->paginate(10);

        return view('pages.penghuni.pembayaran-penghuni', compact(
            'pending', 'cicilan1', 'cicilan2', 'sudahLunas', 'adaTagihan', 'history'
        ));
    }

    public function create(Request $request)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        return $this->createPayment($request);
    }


    public function createPayment(Request $request)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        $request->validate([
            'pembayaran_id' => 'required|integer|exists:pembayarans,id',
        ]);

        $pembayaran = Pembayaran::findOrFail($request->pembayaran_id);

        if ($pembayaran->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses tidak diizinkan.'], 403);
        }

        if ($pembayaran->status !== 'belum_bayar') {
            return response()->json(['message' => 'Pembayaran sudah selesai.'], 422);
        }

        $user = $pembayaran->user;

        // Sudah pecahan cicilan → langsung proses
        if (str_contains($pembayaran->id_pembayaran, '-c1-') || str_contains($pembayaran->id_pembayaran, '-c2-')) {
            return $this->generateSingleSnapToken($pembayaran, $user);
        }

        // Tipe cicilan → buat c1 dulu saja, c2 dibuat setelah c1 lunas
        if ($request->boolean('is_cicilan')) {

            $total    = (int) $pembayaran->nominal;
            $nominal1 = intdiv($total, 2);
            $nominal2 = $total - $nominal1;

            $tanggal1 = $pembayaran->tanggal_pembayaran
                ? Carbon::parse($pembayaran->tanggal_pembayaran)
                : Carbon::now();
            $tanggal2 = $tanggal1->copy()->addWeeks(2);

            // Cek apakah c1 sudah ada (hindari duplikat)
            $existingC1 = Pembayaran::where('user_id', $pembayaran->user_id)
                ->where('id_pembayaran', 'like', $pembayaran->id_pembayaran . '-c1-%')
                ->latest('id')
                ->first();

            if ($existingC1) {
                if ($existingC1->status === 'lunas') {
                    return response()->json(['message' => 'Cicilan pertama sudah lunas.'], 422);
                }

                if ($existingC1->snap_token) {
                    return response()->json([
                        'message'    => 'Snap token reused',
                        'snap_token' => $existingC1->snap_token,
                        'payment_id' => $existingC1->id,
                        'order_id'   => $existingC1->id_pembayaran,
                    ]);
                }

                return $this->generateSingleSnapToken($existingC1, $user);
            }

            // Buat hanya record cicilan 1
            $suffix   = now()->format('YmdHis') . rand(100, 999);
            $orderId1 = $pembayaran->id_pembayaran . '-c1-' . $suffix;

            $pem1 = Pembayaran::create([
                'id_pembayaran'      => $orderId1,
                'user_id'            => $pembayaran->user_id,
                'tanggal_pembayaran' => $tanggal1->toDateString(),
                'nominal'            => $nominal1,
                'tipe_pembayaran'    => 'cicilan',
                'jumlah_cicilan'     => 1,
                'status'             => 'belum_bayar',
                'midtrans_order_id'  => $orderId1,
            ]);

            // Simpan info cicilan2 di midtrans_response supaya verify() bisa buat c2
            $pem1->update([
                'midtrans_response' => json_encode([
                    'cicilan2_nominal'     => $nominal2,
                    'cicilan2_tanggal'     => $tanggal2->toDateString(),
                    'cicilan2_suffix'      => $suffix,
                    'induk_id_pembayaran'  => $pembayaran->id_pembayaran,
                ])
            ]);

            $snapToken1 = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $pem1->id_pembayaran,
                    'gross_amount' => (int) $pem1->nominal,
                ],
                'customer_details' => [
                    'first_name' => $user->nama,
                    'email'      => $user->email,
                ],
            ]);

            $pem1->update(['snap_token' => $snapToken1]);

            return response()->json([
                'message'    => 'Snap token cicilan 1 generated',
                'snap_token' => $snapToken1,
                'payment_id' => $pem1->id,
                'order_id'   => $pem1->id_pembayaran,
            ]);
        }

        // Default: bayar lunas
        return $this->generateSingleSnapToken($pembayaran, $user);
    }

    private function generateSingleSnapToken($pembayaran, $user)
    {
        $suffix     = now()->format('His'); 
        $maxBaseLen = 50 - 1 - strlen($suffix); 
        $base        = substr($pembayaran->id_pembayaran, 0, $maxBaseLen);

        $midtransOrderId = substr($base . '-' . $suffix, 0, 50);

        $email = trim(preg_replace('/\s+/', '', $user->email ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'user' . $user->id . '@placeholder.com';
        }

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $pembayaran->nominal,
            ],
            'customer_details' => [
                'first_name' => trim($user->nama),
                'email'      => $email,
            ],
        ]);

        $pembayaran->update([
            'snap_token'        => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
        ]);

        return response()->json([
            'message'    => 'Snap token generated',
            'snap_token' => $snapToken,
            'payment_id' => $pembayaran->id,
            'order_id'   => $midtransOrderId,
        ]);
    }

    /**
     * Dipanggil dari frontend setelah Midtrans onSuccess
     */
    public function verify(Request $request)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403);
        }

        $request->validate([
            'pembayaran_id' => 'required|integer|exists:pembayarans,id',
        ]);

        $pembayaran = Pembayaran::findOrFail($request->pembayaran_id);

        if ($pembayaran->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses tidak diizinkan.'], 403);
        }

        if ($pembayaran->status === 'lunas') {
            return response()->json(['status' => 'lunas']);
        }

        $pembayaran->update([
            'status'  => 'lunas',
            'paid_at' => now(),
        ]);

        // Jika ini cicilan 1 → buat cicilan 2 sekarang
        if (str_contains($pembayaran->id_pembayaran, '-c1-')) {
            $meta = json_decode($pembayaran->midtrans_response, true);

            $cicilan2Nominal   = $meta['cicilan2_nominal']    ?? null;
            $cicilan2Tanggal   = $meta['cicilan2_tanggal']    ?? null;
            $cicilan2Suffix    = $meta['cicilan2_suffix']     ?? now()->format('YmdHis');
            $indukIdPembayaran = $meta['induk_id_pembayaran'] ?? null;

            if ($cicilan2Nominal && $indukIdPembayaran) {
                $orderId2 = $indukIdPembayaran . '-c2-' . $cicilan2Suffix;

                $existingC2 = Pembayaran::where('id_pembayaran', $orderId2)->first();
                if (!$existingC2) {
                    Pembayaran::create([
                        'id_pembayaran'      => $orderId2,
                        'user_id'            => $pembayaran->user_id,
                        'tanggal_pembayaran' => $cicilan2Tanggal ?? now()->addWeeks(2)->toDateString(),
                        'nominal'            => $cicilan2Nominal,
                        'tipe_pembayaran'    => 'cicilan',
                        'jumlah_cicilan'     => 1,
                        'status'             => 'belum_bayar',
                    ]);
                }
            }
        }

        return response()->json(['status' => 'lunas']);
    }

    public function status(Pembayaran $pembayaran)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        if ($pembayaran->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses tidak diizinkan.'], 403);
        }

        return response()->json([
            'status'             => $pembayaran->status,
            'transaction_status' => $pembayaran->transaction_status,
            'transaction_id'     => $pembayaran->transaction_id,
            'va_number'          => $pembayaran->va_number,
            'payment_type'       => $pembayaran->payment_type,
            'paid_at'            => $pembayaran->paid_at,
            'snap_token'         => $pembayaran->snap_token,
        ]);
    }

    public function pending(Pembayaran $pembayaran)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        if ($pembayaran->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.penghuni.pembayaran-status', compact('pembayaran'));
    }

    public function callback(Request $request)
    {
        try {
            $payload = $request->all();
            $orderId = $request->input('order_id');

            \Log::info('Midtrans callback masuk', ['order_id' => $orderId]);

            if (!$orderId) {
                return response()->json(['message' => 'Invalid payload: order_id is missing'], 400);
            }

            // Cari by midtrans_order_id dulu, fallback ke id_pembayaran
            $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first()
                ?? Pembayaran::where('id_pembayaran', $orderId)->first();

            if (!$pembayaran) {
                return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 404);
            }

            $notif             = new \Midtrans\Notification();
            $transactionStatus = $notif->transaction_status;
            $paymentType       = $notif->payment_type;
            $transactionId     = $notif->transaction_id;
            $fraudStatus       = $notif->fraud_status ?? null;

            $vaNumber = null;
            if (!empty($payload['va_numbers']) && is_array($payload['va_numbers'])) {
                $vaNumber = $payload['va_numbers'][0]['va_number'] ?? null;
            } elseif (!empty($payload['permata_va_number'])) {
                $vaNumber = $payload['permata_va_number'];
            } elseif (!empty($payload['bill_key']) && !empty($payload['biller_code'])) {
                $vaNumber = $payload['biller_code'] . ' / ' . $payload['bill_key'];
            }

            $paidAt = null;
            if (!empty($payload['transaction_time'])) {
                try {
                    $paidAt = Carbon::parse($payload['transaction_time']);
                } catch (\Exception $e) {
                    $paidAt = now();
                }
            }

            $updateData = [
                'transaction_status' => $transactionStatus,
                'payment_type'       => $paymentType,
                'midtrans_response'  => json_encode($payload),
            ];

            if ($vaNumber) $updateData['va_number'] = $vaNumber;

            $alreadySettled = ($pembayaran->status === 'lunas');

            $isSuccessful = ($transactionStatus === 'settlement') || (
                $transactionStatus === 'capture' && in_array($fraudStatus, ['accept', 'challenge'])
            );

            if ($isSuccessful) {
                if (!$alreadySettled) {
                    $updateData['status']         = 'lunas';
                    $updateData['transaction_id'] = $transactionId;
                    $updateData['paid_at']        = $paidAt ?? now();
                }
                $pembayaran->update($updateData);
            } elseif ($transactionStatus === 'pending') {
                if (!$alreadySettled) {
                    $updateData['status']         = 'pending';
                    $updateData['transaction_id'] = $transactionId;
                }
                $pembayaran->update($updateData);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                if (!$alreadySettled) {
                    $updateData['status']         = 'failed';
                    $updateData['transaction_id'] = $transactionId;
                }
                $pembayaran->update($updateData);
            } else {
                $pembayaran->update($updateData);
            }

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            \Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function getHistory()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        $indukDenganCicilan = Pembayaran::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('id_pembayaran', 'like', '%-c1-%')
                  ->orWhere('id_pembayaran', 'like', '%-c2-%');
            })
            ->get()
            ->map(fn($p) => preg_replace('/-c[12]-\d+$/', '', $p->id_pembayaran))
            ->unique()->values()->toArray();

        $payments = Pembayaran::where('user_id', Auth::id())
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        return response()->json([
            'pending' => $payments->where('status', 'belum_bayar')
                ->filter(fn($p) =>
                    !str_contains($p->id_pembayaran, '-c1-') &&
                    !str_contains($p->id_pembayaran, '-c2-') &&
                    !in_array($p->id_pembayaran, $indukDenganCicilan)
                )->first(),
            'history' => $payments->where('status', 'lunas')
                ->filter(fn($p) => !in_array($p->id_pembayaran, $indukDenganCicilan))
                ->values(),
        ]);
    }

    public function finish()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        return redirect()->route('pembayaran.penghuni')->with('payment_status', 'finish');
    }

    public function unfinish()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        return redirect()->route('pembayaran.penghuni')->with('payment_status', 'unfinish');
    }

    public function error()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        return redirect()->route('pembayaran.penghuni')->with('payment_status', 'error');
    }
}