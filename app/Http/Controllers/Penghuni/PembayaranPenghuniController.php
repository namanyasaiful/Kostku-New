<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Notification;
use Exception;

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

        $payments = Pembayaran::where('user_id', Auth::id())
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        // PERBAIKAN: Cari sub-cicilan yang belum bayar terlebih dahulu, jika tidak ada baru ambil data tagihan utama.
        $pending = $payments->where('status', 'belum_bayar')
                            ->sortByDesc('id') // Menjamin cicilan ter-update yang diambil
                            ->first();

        $history = $payments->where('status', 'lunas');

        return view('pages.penghuni.pembayaran-penghuni', compact('pending', 'history'));
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
        return response()->json(['message' => 'Akses ke pembayaran ini tidak diizinkan.'], 403);
    }

    if ($pembayaran->status !== 'belum_bayar') {
        return response()->json(['message' => 'Pembayaran sudah selesai.'], 422);
    }

    $user = $pembayaran->user;

    // Logika Cicilan
    if ((string) ($pembayaran->tipe_pembayaran ?? '') === 'cicilan') {

        // ==================== PERBAIKAN DI SINI ====================
        // Jika ID Pembayaran mengandung kata '-c1-' atau '-c2-', artinya ini
        // adalah data pecahan cicilan. LANGSUNG PROSES, JANGAN DIPOTONG LAGI!
        if (str_contains($pembayaran->id_pembayaran, '-c1-') || str_contains($pembayaran->id_pembayaran, '-c2-')) {
            return $this->generateSingleSnapToken($pembayaran, $user);
        }
        // ===========================================================

        // Paksa agar trigger cicilan 2x terpenuhi untuk tagihan UTAMA
        if ((int) $pembayaran->jumlah_cicilan !== 2) {
            $pembayaran->update(['jumlah_cicilan' => 2]);
            $pembayaran->refresh();
        }

        if ((int) $pembayaran->jumlah_cicilan === 2) {
            $total = (int) $pembayaran->nominal;
            $cicilan1 = intdiv($total, 2);
            $cicilan2 = $total - $cicilan1;

            $tanggal1 = $pembayaran->tanggal_pembayaran ? Carbon::parse($pembayaran->tanggal_pembayaran) : Carbon::now();
            $tanggal2 = $tanggal1->copy()->addWeeks(2);

            // Gunakan suffix pendek (6 digit jam-menit-detik) agar tidak melebihi 50 karakter Midtrans
            $orderSuffixBase = now()->format('His');
            $orderId1 = $pembayaran->id_pembayaran . '-c1-' . $orderSuffixBase;
            $orderId2 = $pembayaran->id_pembayaran . '-c2-' . $orderSuffixBase;

            $pembayaran1 = Pembayaran::where('id_pembayaran', $orderId1)->first();
            $pembayaran2 = Pembayaran::where('id_pembayaran', $orderId2)->first();

            if ($pembayaran2 && $pembayaran2->status === 'lunas') {
                return response()->json([
                    'message'        => 'Cicilan kedua sudah lunas. Tagihan periode berikutnya akan muncul sesuai jadwal.',
                    'snap_tokens'    => [],
                    'payment_ids'    => [],
                    'order_ids'      => [],
                ], 422);
            }

            if (!$pembayaran1) {
                $pembayaran1 = Pembayaran::create([
                    'id_pembayaran'      => $orderId1,
                    'user_id'            => $pembayaran->user_id,
                    'tanggal_pembayaran' => $tanggal1->toDateString(),
                    'nominal'            => $cicilan1,
                    'tipe_pembayaran'    => 'cicilan',
                    'jumlah_cicilan'     => 1,
                    'status'             => 'belum_bayar',
                ]);
            }

            if (!$pembayaran2) {
                $pembayaran2 = Pembayaran::create([
                    'id_pembayaran'      => $orderId2,
                    'user_id'            => $pembayaran->user_id,
                    'tanggal_pembayaran' => $tanggal2->toDateString(),
                    'nominal'            => $cicilan2,
                    'tipe_pembayaran'    => 'cicilan',
                    'jumlah_cicilan'     => 1,
                    'status'             => 'belum_bayar',
                ]);
            }

            $snapTokens = [];
            foreach ([$pembayaran1, $pembayaran2] as $item) {
                $snapTokens[] = Snap::getSnapToken([
                    'transaction_details' => [
                        'order_id'     => $item->id_pembayaran,
                        'gross_amount' => $item->nominal,
                    ],
                    'customer_details' => [
                        'first_name' => $user->nama,
                        'email'      => $user->email,
                    ],
                ]);
            }

            [$snapToken1, $snapToken2] = $snapTokens;
            $pembayaran1->update(['snap_token' => $snapToken1]);
            $pembayaran2->update(['snap_token' => $snapToken2]);

            return response()->json([
                'message'     => 'Snap token generated for cicilan 2x',
                'snap_tokens' => [$snapToken1, $snapToken2],
                'payment_ids' => [$pembayaran1->id, $pembayaran2->id],
                'order_ids'   => [$pembayaran1->id_pembayaran, $pembayaran2->id_pembayaran],
            ]);
        }
    }

    // Default: 1 transaksi tunggal (Lunas)
    return $this->generateSingleSnapToken($pembayaran, $user);
}

/**
 * Helper Helper untuk memproses pembayaran tunggal / pelunasan langsung tanpa potong
 */
private function generateSingleSnapToken($pembayaran, $user)
{
    $snapToken = Snap::getSnapToken([
        'transaction_details' => [
            'order_id'     => $pembayaran->id_pembayaran,
            'gross_amount' => (int) $pembayaran->nominal, // Nominal penuh dari record yang dipilih
        ],
        'customer_details' => [
            'first_name' => $user->nama,
            'email'      => $user->email,
        ],
    ]);

    $pembayaran->update(['snap_token' => $snapToken]);

    return response()->json([
        'message'    => 'Snap token generated',
        'snap_token' => $snapToken,
        'payment_id' => $pembayaran->id,
        'order_id'   => $pembayaran->id_pembayaran,
    ]);
}

    public function status(Pembayaran $pembayaran)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        if ($pembayaran->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses ke pembayaran ini tidak diizinkan.'], 403);
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
            // 1. Ambil payload mentah dari request body
            $payload = $request->all();
            $orderId = $request->input('order_id');

            // 2. Validasi awal: pastikan order_id ada di payload
            if (!$orderId) {
                return response()->json(['message' => 'Invalid payload: order_id is missing'], 400);
            }

            // 3. Cari data pembayaran berdasarkan id_pembayaran sebelum memanggil SDK Midtrans
            // Ini mencegah crash/error 500 jika menerima "Test Notification" acak dari dashboard Midtrans
            $pembayaran = Pembayaran::where('id_pembayaran', $orderId)->first();

            if (!$pembayaran) {
                return response()->json([
                    'message' => 'Pembayaran dengan Order ID ' . $orderId . ' tidak ditemukan.'
                ], 404);
            }

            // 4. Inisialisasi Midtrans Notification (untuk validasi signature key otomatis)
            $notif = new \Midtrans\Notification();

            // Ambil data terverifikasi langsung dari objek SDK Midtrans
            $transactionStatus = $notif->transaction_status;
            $paymentType = $notif->payment_type;
            $transactionId = $notif->transaction_id;
            $fraudStatus = $notif->fraud_status ?? null;

            // 5. Buat struktur log audit internal
            $callbackAudit = [
                'received_at' => now()->toDateTimeString(),
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'transaction_id' => $transactionId,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'payload' => $payload,
            ];

            // 6. Parsing Nomor Virtual Account (VA) atau Biller Code
            $vaNumber = null;
            if (!empty($payload['va_numbers']) && is_array($payload['va_numbers'])) {
                $vaNumber = $payload['va_numbers'][0]['va_number'] ?? null;
            } elseif (!empty($payload['permata_va_number'])) {
                $vaNumber = $payload['permata_va_number'];
            } elseif (!empty($payload['bill_key']) && !empty($payload['biller_code'])) {
                $vaNumber = $payload['biller_code'] . ' / ' . $payload['bill_key'];
            }

            // Parsing Waktu Pembayaran
            $paidAt = null;
            if (!empty($payload['transaction_time'])) {
                try {
                    $paidAt = Carbon::parse($payload['transaction_time']);
                } catch (\Exception $e) {
                    $paidAt = now();
                }
            }

            // Data dasar untuk update database
            $updateData = [
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'midtrans_response' => json_encode(array_merge($callbackAudit, $payload)),
            ];

            if ($vaNumber) {
                $updateData['va_number'] = $vaNumber;
            }
            if ($vaNumber) {
                $updateData['va_number'] = $vaNumber;
            }

            // Idempotency check: jika status di DB sudah 'lunas'
            $alreadySettled = ($pembayaran->status === 'lunas');

            // 7. Evaluasi Status Keberhasilan Transaksi
            $isSuccessful = ($transactionStatus === 'settlement') || (
                $transactionStatus === 'capture' && ($fraudStatus === 'accept' || $fraudStatus === 'challenge')
            );

            if ($isSuccessful) {
                if ($alreadySettled) {
                    // Tetap update response & status transaksi agar data sync tanpa merusak status kelulusan
                    $pembayaran->update($updateData);
                    return response()->json(['message' => 'Already settled'], 200);
                }

                $updateData['status'] = 'lunas';
                $updateData['transaction_id'] = $transactionId;
                $updateData['paid_at'] = $paidAt ?? now();

                $pembayaran->update($updateData);
            }
            // Handle status Pending
            elseif ($transactionStatus === 'pending') {
                if (!$alreadySettled) {
                    $updateData['status'] = 'pending';
                    $updateData['transaction_id'] = $transactionId;
                    $pembayaran->update($updateData);
                } else {
                    $pembayaran->update($updateData);
                }
            }
            // Handle status Gagal/Kedaluwarsa
            elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                if (!$alreadySettled) {
                    $updateData['status'] = 'failed';
                    $updateData['transaction_id'] = $transactionId;
                    $pembayaran->update($updateData);
                } else {
                    $pembayaran->update($updateData);
                }
            }
            // Kondisi status lainnya (misal: challenge)
            else {
                if (!$alreadySettled && $transactionStatus === 'challenge') {
                    $updateData['status'] = 'pending';
                }
                $pembayaran->update($updateData);
            }

            return response()->json(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            // Log error jika terjadi crash tak terduga agar mudah didebug
            \Log::error('Midtrans Callback Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getHistory()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        $payments = Pembayaran::where('user_id', Auth::id())
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        return response()->json([
            'pending' => $payments->where('status', 'belum_bayar')->first(),
            'history' => $payments->where('status', 'lunas')->values(),
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
