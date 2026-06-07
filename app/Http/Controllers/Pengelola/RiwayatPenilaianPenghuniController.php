<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Record;
use App\Models\User;

class RiwayatPenilaianPenghuniController extends Controller
{
    public function viewRiwayatPenilaianPenghuni($userId)
    {
        $penghuni = User::findOrFail($userId);

        $records = Record::with(['kamar.kost'])
            ->where('user_id', $userId)
            ->where('status', 'Disetujui')
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        return view('pages.pengelola.riwayat-penilaian-penghuni', compact('penghuni', 'records'));
    }
}