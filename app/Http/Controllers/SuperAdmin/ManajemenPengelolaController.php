<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kost;
use Illuminate\Http\Request;

class ManajemenPengelolaController extends Controller
{
    public function viewManajemenPengelola(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', 'pengelola')
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('kosts', function ($q) use ($search) {
                    $q->where('nama_kost', 'like', "%{$search}%")
                        ->orWhere('kode_kost', 'like', "%{$search}%");
                });
            })
            ->with('kosts');

        $semuaPengelola    = (clone $query)->get();
        $aktifPengelola    = (clone $query)->where('status', 'Aktif')->get();
        $menungguPengelola = (clone $query)->where('status', 'Menunggu')->get();
        $dibatasiPengelola = (clone $query)->where('status', 'Dibatasi')->get();

        return view('pages.superadmin.manajemen-pengelola', compact(
            'semuaPengelola',
            'aktifPengelola',
            'menungguPengelola',
            'dibatasiPengelola',
            'search'
        ));
}

    public function setujuiPengelola(User $pengelola)
    {
        // Generate kode_kost format KST-001
        $lastKost = Kost::whereNotNull('kode_kost')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastKost && preg_match('/KST-(\d+)/', $lastKost->kode_kost, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        }

        $kodeKost = 'KST-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $pengelola->update(['status' => 'Aktif']);
        $pengelola->kosts()->update(['kode_kost' => $kodeKost]);

        return redirect()->route('manajemen-pengelola.superadmin')
            ->with('success', 'Pengelola berhasil disetujui');
    }

    public function tolakPengelola(User $pengelola)
    {
        $pengelola->kosts()->delete();
        
        \DB::table('users')->where('id', $pengelola->id)->delete();

        return redirect()->route('manajemen-pengelola.superadmin')
            ->with('success', 'Pengelola berhasil ditolak');
    }

    public function batasiPengelola(User $pengelola)
    {
        $pengelola->update(['status' => 'Dibatasi']);

        return redirect()->route('manajemen-pengelola.superadmin')
            ->with('success', 'Akun pengelola berhasil dibatasi');
    }

    public function aktifkanPengelola(User $pengelola)
    {
        $pengelola->update(['status' => 'Aktif']);

        return redirect()->route('manajemen-pengelola.superadmin')
            ->with('success', 'Akun pengelola berhasil diaktifkan');
    }
}