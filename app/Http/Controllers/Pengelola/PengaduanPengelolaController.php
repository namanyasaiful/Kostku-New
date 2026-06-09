<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Models\Kost;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PengaduanDibalasNotification;

class PengaduanPengelolaController extends Controller
{
    public function viewPengaduan()
    {
        if ($redirect = $this->guardPengelola()) {
            return $redirect;
        }

        $kostIds = Kost::where('user_id', Auth::id())->pluck('id');
        $search = request('search_pengaduan');

        $pengaduans = Pengaduan::whereHas('user.penghuni.kamar', function ($q) use ($kostIds) {
                $q->whereIn('kode_kost', $kostIds);
            })
            ->with(['user.penghuni.kamar'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('nama', 'like', '%' . $search . '%');
                })->orWhere('judul', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengelola.pengaduan-pengelola', compact('pengaduans'));
    }

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

    public function pengelolaStorePengaduan(Request $request)
    {
        $request->validate([
            'pengaduan_id' => 'required|exists:pengaduans,id',
            'balasan'      => 'required|string|max:1000',
        ], [
            'balasan.required' => 'Balasan tidak boleh kosong.',
        ]);

        $pengaduan = Pengaduan::findOrFail($request->pengaduan_id);

        if ($pengaduan->balasan) {
            return redirect()->route('pengaduan.pengelola')
                ->with('failed_balasan', 'Pengaduan ini sudah pernah dibalas.');
        }

        $pengaduan->update([
            'balasan' => $request->balasan,
            'status'  => 'proses',
        ]);

        $pengaduan->user->notify(new PengaduanDibalasNotification($pengaduan));

        return redirect()->route('pengaduan.pengelola')
            ->with('success_balasan', true);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,selesai',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update(['status' => $request->status]);

        return redirect()->route('pengaduan.pengelola')
            ->with('success_status', true);
    }
}