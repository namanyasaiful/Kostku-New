<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajemenPengelolaController extends Controller
{
    public function viewManajemenPengelola()
    {
        return view ('pages.superadmin.manajemen-pengelola');
    }
}
