<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\Kost;
use Illuminate\Http\Request;

class KamarPenghuniController extends Controller
{
    public function requestKamar(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);
        $kost = Kost::where('kode_kost', $request->kode_kost)->first();

        if (!$kost || $kamar->kode_kost !== $kost->id) {
            return redirect()->back()->with('error', 'Kode kost tidak valid untuk kamar ini.');
        }

        if ($kamar->status !== 'kosong') {
            return redirect()->back()->with('error', 'Kamar sudah terisi.');
        }

        $activeRequest = Penghuni::where('user_id', Auth::id())
            ->whereIn('status_request', ['menunggu', 'disetujui'])
            ->whereNull('tanggal_keluar')
            ->exists();

        if ($activeRequest) {
            return redirect()->back()->with('error', 'Anda masih memiliki permintaan aktif atau sudah menghuni kost.');
        }

        Penghuni::create([
            'user_id' => Auth::id(),
            'nomor_kamar' => $kamar->id,
            'status_request' => 'menunggu',
            'tanggal_masuk' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan bergabung berhasil dikirim. Menunggu persetujuan pengelola.');
    }

    public function approveRequest($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        if ($penghuni->status_request !== 'menunggu') {
            return redirect()->back()->with('error', 'Status request tidak valid.');
        }

        DB::transaction(function () use ($penghuni) {
            $penghuni->kamar->update([
                'user_id' => $penghuni->user_id,
                'status'  => 'terisi'
            ]);

            $penghuni->update([
                'status_request' => 'disetujui',
            ]);
        });

        return redirect()->back()->with('success', 'Request disetujui.');
    }

    public function penghuniOut(Request $request)
    {
        $penghuni = Penghuni::where('user_id', Auth::id())
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->first();

        if (!$penghuni) {
            return redirect()->back()->with('error', 'Data penghuni aktif tidak ditemukan.');
        }

        $penghuni->update([
            'tanggal_keluar' => Carbon::now(),
            'notes_penghuni' => $request->alasan_keluar,
        ]);

        return redirect()->back()->with('success', 'Permintaan keluar kost telah dikirim ke pengelola.');
    }

    public function checkoutKamar($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        if ($penghuni->status_request !== 'disetujui') {
            return redirect()->back()->with('error', 'Penghuni tidak sedang aktif.');
        }

        DB::transaction(function () use ($penghuni) {
            $penghuni->kamar->update([
                'user_id' => null,
                'status'  => 'kosong'
            ]);

            $penghuni->update([
                'tanggal_keluar' => Carbon::now()
            ]);
        });

        return redirect()->back()->with('success', 'Checkout berhasil.');
    }
}
