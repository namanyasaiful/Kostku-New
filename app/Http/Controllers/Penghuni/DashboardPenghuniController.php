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
            ->with('join_success', true);
    }

    public function leaveKost(Request $request)
    {
        $request->validate([
            'alasan_keluar' => 'required|string'
        ], [
            'alasan_keluar.required' => 'Alasan keluar wajib diisi.',
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

        return redirect()
            ->route('dashboard.penghuni')
            ->with('leave_success', true);
    }

    public function validasiKode(Request $request)
    {
        $request->validate([
            'kode_kost' => 'required'
        ]);

        $kost = Kost::with('user')
            ->where('kode_kost', $request->kode_kost)
            ->first();

        if (!$kost) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kost tidak ditemukan.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kost->id,
                'nama_kost' => $kost->nama_kost,
                'alamat' => $kost->alamat_kost,
                'pemilik' => $kost->user->nama,
            ]
        ]);
    }
}
