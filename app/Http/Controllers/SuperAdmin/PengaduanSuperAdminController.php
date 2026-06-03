<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaduanSuperAdminController extends Controller
{
    public function viewPengaduanSuperAdmin()
    {
        return view('pages.superadmin.pengaduan-superadmin');
    }
}
