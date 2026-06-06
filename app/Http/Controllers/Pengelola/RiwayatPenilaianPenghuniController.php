<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPenilaianPenghuniController extends Controller
{
    public function viewRiwayatPenilaianPenghuni()
    {
        return view('pages.pengelola.riwayat-penilaian-penghuni');
    }
}
