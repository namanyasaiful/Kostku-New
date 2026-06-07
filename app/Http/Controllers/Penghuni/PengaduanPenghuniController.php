<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanPenghuniController extends Controller
{
    private function checkPenghuniAktif()
    {
        return \App\Models\Penghuni::where('user_id', auth()->id())
            ->where('status_request', 'disetujui')
            ->whereNull('tanggal_keluar')
            ->exists();
    }

    public function viewPengaduan()
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403, 'Anda belum terdaftar sebagai penghuni kost.');
        }

        $pengaduans = Pengaduan::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.penghuni.pengaduan-penghuni', compact('pengaduans'));
    }

    public function storePengaduanPenghuni(Request $request)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403);
        }

        $request->validate([
            'judul'           => 'required|string|max:255',
            'isi'             => 'required|string',
            'bukti_pengaduan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ], [
            'judul.required' => 'Judul pengaduan wajib diisi.',
            'isi.required'   => 'Deskripsi pengaduan wajib diisi.',
            'bukti_pengaduan.mimes' => 'File harus berupa JPG, PNG, atau PDF.',
            'bukti_pengaduan.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        try {

            $path = null;

            if ($request->hasFile('bukti_pengaduan')) {
                $path = $request->file('bukti_pengaduan')
                    ->store('pengaduan', 'public');
            }

            Pengaduan::create([
                'judul'           => $request->judul,
                'isi'             => $request->isi,
                'status'          => 'baru',
                'user_id'         => auth()->id(),
                'bukti_pengaduan' => $path,
            ]);

            return redirect()->route('pengaduan.penghuni')
                ->with('success_pengaduan', true);
        } catch (\Exception $e) {

            return redirect()->route('pengaduan.penghuni')
                ->with('failed_pengaduan', true);
        }
    }

    public function batalPengaduan($id)
    {
        if (!$this->checkPenghuniAktif()) {
            abort(403);
        }

        $pengaduan = Pengaduan::where('id', $id)
            ->where('user_id', auth()->id())  // pastikan milik user sendiri
            ->where('status', 'baru')         // hanya yang masih baru
            ->firstOrFail();

        $pengaduan->delete();

        return redirect()->route('pengaduan.penghuni')->with('success_dibatalkan', true);
    }
}
