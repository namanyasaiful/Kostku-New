<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Kost;
use App\Models\Penghuni;

class PengaduanPengelolaController extends Controller
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
    public function viewPengaduan()
    {
        if ($redirect = $this->guardPengelola()) {
            return $redirect;
        }

        $kostIds = Kost::where('user_id', Auth::id())->pluck('id');

        $pengaduans = Pengaduan::whereHas('user.penghuni.kamar', function ($q) use ($kostIds) {
            $q->whereIn('kode_kost', $kostIds);
        })
            ->with(['user.penghuni.kamar'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.pengelola.pengaduan-pengelola', compact('pengaduans'));
    }

    public function pengelolaStorePengaduan(Request $request)
    {
        $request->validate([
            'pengaduan_id' => 'required|exists:pengaduans,id',
            'balasan' => 'required|string',
            'status' => 'required|in:proses,selesai',
        ]);

        $pengaduan = Pengaduan::findOrFail($request->pengaduan_id);

        $pengaduan->update([
            'balasan' => $request->balasan,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with(['success' => 'Balasan pengaduan berhasil dikirim.', 'status_updated' => $request->status]);
    }
}
