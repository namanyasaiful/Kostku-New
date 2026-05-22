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

    public function kodeKost()
    {
        return view('pages.penghuni.kode-kost');
    }

    public function joinKost(Request $request)
    {
        $request->validate([
            'kode_kost' => 'required|string',
        ]);

        $kost = Kost::where('kode_kost', $request->kode_kost)->first();

        if (!$kost) {
            return redirect()->back()->with('error', 'Kode kost tidak valid.');
        }

        $kamar = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'kosong')
            ->first();

        if (!$kamar) {
            return redirect()->back()->with('error', 'Maaf, tidak ada kamar kosong tersedia di kost ini.');
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
}
