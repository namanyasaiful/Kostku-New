<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembayaranSuperAdminController extends Controller
{
    public function viewPembayaranSuperAdmin()
    {
        return view('pages.superadmin.pembayaran-superadmin');
    }
}
