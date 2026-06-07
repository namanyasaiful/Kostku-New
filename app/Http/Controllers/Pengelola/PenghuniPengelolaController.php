<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Penghuni;
use App\Models\Kost;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Pembayaran;


class PenghuniPengelolaController extends Controller
{
    public function guardPengelola()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'pengelola') {
            if (Auth::user()->role === 'penghuni') {
                return redirect()->route('dashboard.penghuni');
            }

            return redirect()->route('dashboard.superadmin');
        }

        return null;
    }

    public function viewPenghuni()
    {
        $kostIds = Kost::where('user_id', Auth::id())->pluck('id');

        $query = Penghuni::query()
            ->whereHas('kamar', function ($q) use ($kostIds) {
                $q->whereIn('kode_kost', $kostIds);
            })
            ->with(['user', 'kamar.kost']);

        $daftarPenghuni = (clone $query)
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->get();

        $permintaanMasuk = (clone $query)
            ->where('status_request', 'menunggu')
            ->get();

        $permintaanKeluar = (clone $query)
            ->where('status_request', 'disetujui')
            ->whereNotNull('tanggal_keluar')
            ->get();

        $kamarKosong = Kamar::whereIn('kode_kost', $kostIds)->where('status', 'kosong')->get();

        return view('pages.pengelola.penghuni-pengelola', compact('daftarPenghuni', 'permintaanMasuk', 'permintaanKeluar', 'kamarKosong'));
    }

    public function approvePenghuni(Request $request, $id)
    {
        $request->validate([
            'nomor_kamar' => 'required|exists:kamars,id',
        ]);

        $penghuni = Penghuni::findOrFail($id);

        DB::transaction(function () use ($penghuni, $request) {
            $kamar = Kamar::findOrFail($request->nomor_kamar);

            if ($kamar->status !== 'kosong') {
                throw new \Exception('Kamar yang dipilih sudah terisi.');
            }

            $penghuni->update([
                'nomor_kamar' => $kamar->id,
                'status_request' => 'disetujui'
            ]);

            $kamar->update([
                'user_id' => $penghuni->user_id,
                'status'  => 'terisi'
            ]);

            // Buat tagihan awal otomatis saat penghuni baru disetujui masuk.
            // (tanggal_pembayaran = sekarang sesuai permintaan Anda)
            $idPembayaran = 'penghuni-' . $penghuni->id . '-kamar-' . $kamar->id . '-' . now()->format('YmdHis');

            // Anti duplikasi sederhana: kalau id_pembayaran deterministic sudah ada, jangan buat lagi.
            // Karena id_pembayaran memakai timestamp, duplikasi biasanya hanya terjadi jika approve dipanggil ulang cepat.
            // Untuk kasus itu, minimal cek berdasarkan user_id + tanggal_pembayaran.
            $existing = Pembayaran::where('user_id', $penghuni->user_id)
                ->whereDate('tanggal_pembayaran', now()->toDateString())
                ->where('nominal', (int) $kamar->harga)
                ->exists();

            if (!$existing) {
                Pembayaran::create([
                    'id_pembayaran' => $idPembayaran,
                    'user_id' => $penghuni->user_id,
                    'tanggal_pembayaran' => now()->toDateString(),
                    'nominal' => (int) $kamar->harga,
                    'tipe_pembayaran' => null,
                    'jumlah_cicilan' => 1,
                    'status' => 'belum_bayar',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Penghuni berhasil disetujui.');
    }

    public function rejectPermintaanKeluar($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        // Ketika permintaan keluar ditolak, penghuni harus kembali menjadi penghuni aktif.
        // Query daftar penghuni memakai: status_request = 'disetujui' dan tanggal_keluar IS NULL.
        $penghuni->update([
            'status_request' => 'disetujui',
            'tanggal_keluar' => null,
        ]);

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak.');
    }

    public function rejectPermintaanMasuk($id)
    {
        Penghuni::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Permintaan masuk berhasil ditolak.'
        );
    }


    public function approveKeluar(Request $request, $id)
    {
        $request->validate([
            'skor_pembayaran' => 'required|in:Baik,Perlu Perhatian,Buruk',
            'skor_sikap' => 'required|in:Baik,Perlu Perhatian,Buruk',
            'skor_perawatan_fasilitas' => 'required|in:Baik,Perlu Perhatian,Buruk',
            'catatan' => 'required|string',
            'bukti' => 'nullable|mimes:pdf|max:10240',
        ]);

        $penghuni = Penghuni::findOrFail($id);

        DB::transaction(function () use ($penghuni, $request) {
            $isRedflag = (
                $request->input('skor_pembayaran') === 'Buruk' &&
                $request->input('skor_sikap') === 'Buruk' &&
                $request->input('skor_perawatan_fasilitas') === 'Buruk'
            ) ? 'yes' : 'no';

            // $buktiFilePath = $request->file('bukti')->store('bukti', 'public');
            $buktiFilePath = null;

            if ($request->hasFile('bukti')) {
                $buktiFilePath = $request->file('bukti')->store('bukti', 'public');
            }

            if ($penghuni->kamar) {
                $penghuni->kamar->update([
                    'user_id' => null,
                    'status' => 'kosong'
                ]);
            }

            $kamarId = $penghuni->nomor_kamar;

            if (empty($kamarId)) {
                // kamar_id nullable, tapi guard ini mencegah FK error jika kolom tidak terisi.
                $kamarId = null;
            }

            \App\Models\Record::create([
                'user_id' => $penghuni->user_id,
                'kamar_id' => $kamarId,

                'tanggal_masuk' => optional($penghuni->tanggal_masuk)->toDateString(),
                'tanggal_keluar' => now()->toDateString(),

                'is_redflag' => $isRedflag,

                'skor_pembayaran' => $request->input('skor_pembayaran'),
                'skor_sikap' => $request->input('skor_sikap'),
                'skor_perawatan_fasilitas' => $request->input('skor_perawatan_fasilitas'),

                'catatan' => $request->input('catatan'),
                'bukti' => $buktiFilePath,
            ]);

            $penghuni->delete();
        });

        return redirect()->back()->with('success', 'Permintaan keluar berhasil dikonfirmasi');
    }
}
