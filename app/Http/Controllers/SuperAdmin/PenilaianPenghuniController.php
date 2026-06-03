<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenilaianPenghuniController extends Controller
{
    public function viewPenilaianPenghuni()
    {
        return view('pages.superadmin.penilaian-penghuni');
    }
}
