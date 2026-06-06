<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranSuperAdminController extends Controller
{
    public function viewPembayaranSuperAdmin(Request $request)
    {
        $search = $request->input('search');

        $pembayarans = Pembayaran::with(['user.penghuni.kamar.kost'])
            ->when($search, function ($query, $search) {
                $query->where('id_pembayaran', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.superadmin.pembayaran-superadmin', compact('pembayarans', 'search'));
    }
}