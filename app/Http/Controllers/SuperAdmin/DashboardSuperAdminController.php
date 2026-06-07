<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Pengaduan;
use App\Models\Kost;
use Carbon\Carbon;

class DashboardSuperAdminController extends Controller
{
    public function viewDashboard()
    {
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ===== KARTU STATISTIK =====
        $totalPengelola  = User::where('role', 'pengelola')->count();
        $totalPenghuni   = User::where('role', 'penghuni')->count();
        $totalKost       = Kost::count();
        $totalTransaksi  = Pembayaran::count();

        // Naik/turun
        $pengelolaCurrent   = User::where('role', 'pengelola')->whereDate('created_at', $today)->count();
        $pengelolaYesterday = User::where('role', 'pengelola')->whereDate('created_at', $yesterday)->count();

        $penghuniCurrent   = User::where('role', 'penghuni')->whereDate('created_at', $today)->count();
        $penghuniYesterday = User::where('role', 'penghuni')->whereDate('created_at', $yesterday)->count();

        $kostCurrent   = Kost::whereDate('created_at', $today)->count();
        $kostYesterday = Kost::whereDate('created_at', $yesterday)->count();

        $transaksiCurrent   = Pembayaran::whereDate('created_at', $today)->count();
        $transaksiYesterday = Pembayaran::whereDate('created_at', $yesterday)->count();

        // ===== CHART =====
        $chartData   = [];
        $chartLabels = [];

        for ($i = 10; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M');
            $chartData[]   = User::where('role', '!=', 'superadmin')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // ===== PEMBAYARAN & PENGADUAN TERBARU =====
        $pembayaranTerbaru = Pembayaran::with('user')->latest()->take(3)->get();
        $pengaduanTerbaru  = Pengaduan::with('user')->latest()->take(3)->get();

        return view('pages.superadmin.dashboard-superadmin', compact(
            'totalPengelola', 'totalPenghuni', 'totalKost', 'totalTransaksi',
            'pengelolaCurrent', 'pengelolaYesterday',
            'penghuniCurrent', 'penghuniYesterday',
            'kostCurrent', 'kostYesterday',
            'transaksiCurrent', 'transaksiYesterday',
            'chartData', 'chartLabels',
            'pembayaranTerbaru', 'pengaduanTerbaru',
        ));
    }
}