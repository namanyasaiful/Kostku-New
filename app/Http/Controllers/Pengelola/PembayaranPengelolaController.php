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

        // Ambil user_id penghuni yang kamarnya ada di kost milik pengelola ini
        $penghuniUserIds = \App\Models\Kamar::whereIn('kode_kost', $kosts)
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $pembayarans = \App\Models\Pembayaran::whereIn('user_id', $penghuniUserIds)
            ->with(['user', 'user.penghuni.kamar'])
            ->orderBy('tanggal_pembayaran', 'desc')
            ->paginate(10);

        return view('pages.pengelola.pembayaran-pengelola', compact('pembayarans'));
    }
}

