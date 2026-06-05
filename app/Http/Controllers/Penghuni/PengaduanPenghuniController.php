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
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'bukti_pengaduan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('bukti_pengaduan')) {
            $path = $request->file('bukti_pengaduan')->store('pengaduan', 'public');
        }

        Pengaduan::create([
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'status'          => 'baru',
            'user_id'         => auth()->user()->id,
            'bukti_pengaduan' => $path,
        ]);

        return redirect()->route('pengaduan.penghuni')->with('success', 'Pengaduan berhasil dikirim WOEE!');
    }

    public function batalPengaduan($id)
    {
        $pengaduan = Pengaduan::where('id', $id)
            ->where('user_id', auth()->id())  // pastikan milik user sendiri
            ->where('status', 'baru')         // hanya yang masih baru
            ->firstOrFail();

        $pengaduan->delete();

        return redirect()->route('pengaduan.penghuni')
            ->with('success', 'Pengaduan berhasil dibatalkan.');
    }
}
