<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Record;
use Illuminate\Http\Request;

class ManajemenPenghuniController extends Controller
{
    public function viewManajemenPenghuni(Request $request)
    {
        $search = $request->input('search_penghuni');

        $query = User::with(['penghuni.kamar.kost'])
            ->where('role', 'penghuni')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                       ->orWhere('email', 'like', "%$search%");
                });
            });

        $semuaPenghuni    = (clone $query)->get();
        $aktifPenghuni    = (clone $query)->where('status', 'Aktif')->get();
        $dibatasiPenghuni = (clone $query)->where('status', 'Dibatasi')->get();

        return view('pages.superadmin.manajemen-penghuni', compact(
            'semuaPenghuni',
            'aktifPenghuni',
            'dibatasiPenghuni'
        ));
    }

    public function batasiPenghuni(User $penghuni)
    {
        $penghuni->update(['status' => 'Dibatasi']);

        return redirect()
            ->route('manajemen-penghuni.superadmin')
            ->with('success', 'Akun penghuni berhasil dibatasi.');
    }

    public function aktifkanPenghuni(User $penghuni)
    {
        $penghuni->update(['status' => 'Aktif']);

        return redirect()
            ->route('manajemen-penghuni.superadmin')
            ->with('success', 'Akun penghuni berhasil diaktifkan.');
    }
}