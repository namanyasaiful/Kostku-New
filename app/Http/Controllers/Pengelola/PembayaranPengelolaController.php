<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PembayaranPengelolaController extends Controller
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
    public function viewPembayaran()
    {
        if ($redirect = $this->guardPengelola()) {
            return $redirect;
        }

        $kosts = \App\Models\Kost::where('user_id', Auth::id())->pluck('id');

        $penghuniUserIds = \App\Models\Kamar::whereIn('kode_kost', $kosts)
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $search = request('search_pembayaran');

        $pembayarans = \App\Models\Pembayaran::whereIn('user_id', $penghuniUserIds)
            ->with(['user', 'user.penghuni.kamar'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tanggal_pembayaran', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Pendapatan bulan ini
        $pendapatanBulanIni = \App\Models\Pembayaran::whereIn('user_id', $penghuniUserIds)
            ->where('status', 'lunas')
            ->whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('nominal');

        // Pendapatan bulan lalu (untuk perbandingan)
        $pendapatanBulanLalu = \App\Models\Pembayaran::whereIn('user_id', $penghuniUserIds)
            ->where('status', 'lunas')
            ->whereMonth('tanggal_pembayaran', now()->subMonth()->month)
            ->whereYear('tanggal_pembayaran', now()->subMonth()->year)
            ->sum('nominal');

        // Total transaksi bulan ini
        $totalTransaksiBulanIni = \App\Models\Pembayaran::whereIn('user_id', $penghuniUserIds)
            ->where('status', 'lunas')
            ->whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->count();

        // Total transaksi kemarin
        $totalTransaksiKemarin = \App\Models\Pembayaran::whereIn('user_id', $penghuniUserIds)
            ->where('status', 'lunas')
            ->whereDate('tanggal_pembayaran', now()->subDay()->toDateString())
            ->count();

        return view('pages.pengelola.pembayaran-pengelola', compact(
            'pembayarans',
            'pendapatanBulanIni',
            'pendapatanBulanLalu',
            'totalTransaksiBulanIni',
            'totalTransaksiKemarin',
            'search'
        ));
    }
}

