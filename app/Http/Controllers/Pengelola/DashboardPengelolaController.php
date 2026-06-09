<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\Pengaduan;
use Carbon\Carbon;

class DashboardPengelolaController extends Controller
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
    public function viewDashboard()
    {
        if ($redirect = $this->guardPengelola()) {
            return $redirect;
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $kost = Kost::where('user_id', Auth::id())->first();

        $totalPenghuni = Penghuni::whereHas('kamar', function ($query) use ($kost) {
            $query->where('kode_kost', $kost->id);
        })
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->count();

        $penghuniCurrent = Penghuni::whereHas('kamar', function ($query) use ($kost) {
            $query->where('kode_kost', $kost->id);
        })
            ->where('status_request', 'disetujui')
            ->whereDate('created_at', $today)
            ->count();

        $penghuniYesterday = Penghuni::whereHas('kamar', function ($query) use ($kost) {
            $query->where('kode_kost', $kost->id);
        })
            ->where('status_request', 'disetujui')
            ->whereDate('created_at', $yesterday)
            ->count();

        $kamarTerisiCurrent = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'terisi')
            ->whereDate('updated_at', $today)
            ->count();

        $kamarTerisiYesterday = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'terisi')
            ->whereDate('updated_at', $yesterday)
            ->count();

        $kamarKosongCurrent = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'kosong')
            ->whereDate('updated_at', $today)
            ->count();

        $kamarKosongYesterday = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'kosong')
            ->whereDate('updated_at', $yesterday)
            ->count();

        $kamarTerisi = Kamar::where('kode_kost', $kost->id)
            ->where('status', 'terisi')
            ->count();

        $kamarKosong = Kamar::where(
            'kode_kost',
            $kost->id
        )
            ->where('status', 'kosong')
            ->count();

        $penghuniIds = Penghuni::whereHas('kamar', function ($query) use ($kost) {
            $query->where('kode_kost', $kost->id);
        })
            ->pluck('user_id');

        $pendapatanCurrent = Pembayaran::whereIn('user_id', $penghuniIds)
            ->where('status', 'lunas')
            ->whereDate('paid_at', $today)
            ->sum('nominal');

        $pendapatanYesterday = Pembayaran::whereIn('user_id', $penghuniIds)
            ->where('status', 'lunas')
            ->whereDate('paid_at', $yesterday)
            ->sum('nominal');

        $pendapatanBulanIni = Pembayaran::whereIn(
            'user_id',
            $penghuniIds
        )
            ->where('status', 'lunas')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('nominal');

        $pembayaranTerbaru = Pembayaran::with('user')
            ->whereIn('user_id', $penghuniIds)
            ->where('status', 'lunas')
            ->latest('paid_at')
            ->take(5)
            ->get();

        $pengaduanTerbaru = Pengaduan::with('user')
            ->whereIn('user_id', $penghuniIds)
            ->latest()
            ->take(5)
            ->get();

        return view(
            'pages.pengelola.dashboard-pengelola',
            compact(
                'kost',

                'totalPenghuni',
                'kamarTerisi',
                'kamarKosong',
                'pendapatanBulanIni',

                'penghuniCurrent',
                'penghuniYesterday',

                'kamarTerisiCurrent',
                'kamarTerisiYesterday',

                'kamarKosongCurrent',
                'kamarKosongYesterday',

                'pendapatanCurrent',
                'pendapatanYesterday',

                'pembayaranTerbaru',
                'pengaduanTerbaru'
            )
        );
    }
}