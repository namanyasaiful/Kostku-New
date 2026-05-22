<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Penghuni;
use App\Models\Kost;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenghuniPengelolaController extends Controller
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

    public function viewPenghuni()
    {
        $kostIds = Kost::where('user_id', Auth::id())->pluck('id');
        $kamarIds = Kamar::whereIn('kode_kost', $kostIds)->pluck('id');

        $query = Penghuni::query()
            ->whereHas('kamar', function ($q) use ($kostIds) {
                $q->whereIn('kode_kost', $kostIds);
            })
            ->with(['user', 'kamar.kost']);

        $daftarPenghuni = (clone $query)
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->get();

        $permintaanMasuk = (clone $query)
            ->where('status_request', 'menunggu')
            ->get();

        $permintaanKeluar = (clone $query)
            ->where('status_request', 'disetujui')
            ->whereNotNull('tanggal_keluar')
            ->get();

        $kamarKosong = Kamar::whereIn('kode_kost', $kostIds)->where('status', 'kosong')->get();

        return view('pages.pengelola.penghuni-pengelola', compact('daftarPenghuni', 'permintaanMasuk', 'permintaanKeluar', 'kamarKosong'));
    }

    public function approvePenghuni(Request $request, $id)
    {
        $penghuni = Penghuni::findOrFail($id);

        DB::transaction(function () use ($penghuni, $request) {
            // Update kamar jika pengelola memilih kamar lain di modal
            if ($request->has('nomor_kamar') && $request->nomor_kamar) {
                $penghuni->update(['nomor_kamar' => $request->nomor_kamar]);
                $penghuni->refresh();
            }

            $penghuni->update(['status_request' => 'disetujui']);

            $penghuni->kamar->update([
                'user_id' => $penghuni->user_id,
                'status' => 'terisi'
            ]);
        });

        return redirect()->back()->with('success', 'Penghuni berhasil disetujui.');
    }

    public function rejectPenghuni($id)
    {
        $penghuni = Penghuni::findOrFail($id);
        $penghuni->update(['status_request' => 'ditolak']);

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak.');
    }

    public function approveKeluar($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        DB::transaction(function () use ($penghuni) {
            // Kosongkan kamar
            if ($penghuni->kamar) {
                $penghuni->kamar->update([
                    'user_id' => null,
                    'status' => 'kosong'
                ]);
            }
        });

        return redirect()->back()->with('success', 'Checkout berhasil disetujui.');
    }
}
