<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanSuperAdminController extends Controller
{
    public function viewPengaduanSuperAdmin(Request $request)
    {
        $search = $request->input('search');

        $pengaduans = Pengaduan::with(['user.penghuni.kamar.kost'])
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.superadmin.pengaduan-superadmin', compact('pengaduans', 'search'));
    }
}