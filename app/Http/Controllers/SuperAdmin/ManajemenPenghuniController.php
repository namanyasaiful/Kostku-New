<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajemenPenghuniController extends Controller
{
    public function viewManajemenPenghuni()
    {
        return view('pages.superadmin.manajemen-penghuni');
    }
}
