<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanPenghuniController extends Controller
{
    public function viewPengaduan()
    {
        $pengaduans = Pengaduan::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.penghuni.pengaduan-penghuni', compact('pengaduans'));
    }

    public function storePengaduanPenghuni(Request $request)
    {

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        Pengaduan::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'status' => 'baru',
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('pengaduan.penghuni')->with('success', 'Pengaduan berhasil dikirim WOEE!');
    }
}
