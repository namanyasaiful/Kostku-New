<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Penghuni;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardPenghuniController extends Controller
{
    public function viewDashboard()
    {
        return view('pages.penghuni.dashboard.dashboard-penghuni');
    }

    public function joinKost(Request $request)
    {
        $request->validate([
            'kode_kost' => 'required'
        ]);

        $kost = Kost::where(
            'kode_kost',
            trim($request->kode_kost)
        )->first();

        if (!$kost) {
            return back()->with(
                'error',
                'Kode kost tidak ditemukan'
            );
        }

        $kamar = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'kosong')
            ->first();

        if (!$kamar) {
            return back()->with(
                'error',
                'Tidak ada kamar kosong'
            );
        }

        $existing = Penghuni::where(
            'user_id',
            Auth::id()
        )
            ->whereNull('tanggal_keluar')
            ->whereIn('status_request', [
                'menunggu',
                'disetujui'
            ])
            ->exists();

        if ($existing) {
            return back()->with(
                'error',
                'Anda sudah memiliki pengajuan aktif'
            );
        }

        Penghuni::create([
            'user_id' => Auth::id(),
            'nomor_kamar' => $kamar->id,
            'status_request' => 'menunggu',
            'tanggal_masuk' => now(),
        ]);

        return redirect()
            ->route('dashboard.penghuni')
            ->with(
                'success',
                'Permintaan bergabung berhasil dikirim'
            );

        // dd($request->all());
    }

    public function leaveKost(Request $request)
    {
        $request->validate([
            'alasan_keluar' => 'required|string'
        ]);

        $penghuni = Penghuni::where('user_id', Auth::id())
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->first();

        if (!$penghuni) {
            return back()->with('error', 'Data penghuni tidak ditemukan.');
        }

        $penghuni->update([
            'tanggal_keluar' => now(),
        ]);

        return back()->with(
            'success',
            'Permintaan keluar berhasil dikirim.'
        );
    }

    // public function joinKost(Request $request)
    // {
    //     $request->validate([
    //         'kode_kost' => 'required|string',
    //     ]);

    //     $kodeKost = trim($request->kode_kost);

    //     $kost = Kost::where('kode_kost', $kodeKost)->first();

    //     if (!$kost) {
    //         return redirect()->back()->with('error', 'Kode kost tidak valid.');
    //     }

    //     $kamar = Kamar::where('kode_kost', $kost->id)
    //         ->where('status', 'kosong')
    //         ->first();

    //     if (!$kamar) {
    //         return redirect()->back()->with('error', 'Maaf, tidak ada kamar kosong tersedia di kost ini.');
    //     }

    //     $activeRequest = Penghuni::where('user_id', Auth::id())
    //         ->whereIn('status_request', ['menunggu', 'disetujui'])
    //         ->whereNull('tanggal_keluar')
    //         ->exists();

    //     if ($activeRequest) {
    //         return redirect()->back()->with('error', 'Anda masih memiliki permintaan aktif atau sudah menghuni kost.');
    //     }

    //     Penghuni::create([
    //         'user_id' => Auth::id(),
    //         'nomor_kamar' => $kamar->id,
    //         'status_request' => 'menunggu',
    //         'tanggal_masuk' => Carbon::now(),
    //     ]);

    //     return redirect()->back()->with('success', 'Permintaan bergabung berhasil dikirim. Menunggu persetujuan pengelola.');
    // }
}
