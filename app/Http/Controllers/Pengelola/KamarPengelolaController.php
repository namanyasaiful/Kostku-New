<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\User;
use App\Models\Kost;

class KamarPengelolaController extends Controller
{
    public function viewKamar()
    {
        if (!Auth::check() || Auth::user()->role !== 'pengelola') {
            if (Auth::check() && Auth::user()->role === 'penghuni') {
                return redirect()->route('dashboard.penghuni');
            }
            return redirect()->route('login');
        }

        $kosts = Kost::where('user_id', Auth::id())->get();
        $kostIds = $kosts->pluck('id');
        $search = request('search_kamar');

        $allKamars = Kamar::whereIn('kode_kost', $kostIds)
            ->with('penghuni')
            ->when($search, fn($q) => $q->where('nomor_kamar', 'like', '%' . $search . '%'))
            ->paginate(10, ['*'], 'semua_page')
            ->withQueryString();

        $terisiKamars = Kamar::whereIn('kode_kost', $kostIds)
            ->where('status', 'terisi')
            ->with('penghuni')
            ->when($search, fn($q) => $q->where('nomor_kamar', 'like', '%' . $search . '%'))
            ->paginate(10, ['*'], 'terisi_page')
            ->withQueryString();

        $kosongKamars = Kamar::whereIn('kode_kost', $kostIds)
            ->where('status', 'kosong')
            ->with('penghuni')
            ->when($search, fn($q) => $q->where('nomor_kamar', 'like', '%' . $search . '%'))
            ->paginate(10, ['*'], 'kosong_page')
            ->withQueryString();

        return view('pages.pengelola.kamar-pengelola', compact('kosts', 'allKamars', 'terisiKamars', 'kosongKamars'));
    }

    public function storeKamar(Request $request)
    {
        // Cek kost dulu sebelum validasi
        $kost = Kost::where('user_id', Auth::id())->first();

        if (!$kost) {
            return redirect()->back()->withInput()->with('error', 'Anda harus membuat data kost terlebih dahulu!');
        }

        $request->validate([
            'nomor_kamar' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('kamars', 'nomor_kamar')->where('kode_kost', $kost->id),
            ],
            'tipe_kamar'  => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'fasilitas'   => 'required|string',
        ], [
            'nomor_kamar.required' => 'Nomor kamar wajib diisi.',
            'nomor_kamar.unique'   => 'Nomor kamar sudah digunakan di kost ini.',
            'tipe_kamar.required'  => 'Tipe kamar wajib dipilih.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'harga.min'            => 'Harga tidak boleh negatif.',
            'fasilitas.required'   => 'Fasilitas wajib diisi.',
        ]);

        $kost->kamars()->create([
            'nomor_kamar' => $request->nomor_kamar,
            'kode_kost'   => $kost->id,
            'tipe_kamar'  => $request->tipe_kamar,
            'harga'       => $request->harga,
            'status'      => 'kosong',
            'fasilitas'   => $request->fasilitas,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('kamar.pengelola')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function updateKamar(Request $request, $id)
    {
        $request->validate([
            'nomor_kamar' => 'required|string|max:255',
            'tipe_kamar'  => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'fasilitas'   => 'required|string',
        ]);

        $kostIds = Kost::where('user_id', Auth::id())->pluck('id');
        $kamar = Kamar::whereIn('kode_kost', $kostIds)->findOrFail($id);

        $kamar->update([
            'nomor_kamar' => $request->nomor_kamar,
            'tipe_kamar'  => $request->tipe_kamar,
            'harga'       => $request->harga,
            'fasilitas'   => $request->fasilitas,
        ]);

        return redirect()->back()->with('success', 'Kamar berhasil diperbarui!');
    }

    public function deleteKamar($id)
    {
        $kostIds = Kost::where('user_id', Auth::id())->pluck('id');
        $kamar = Kamar::whereIn('kode_kost', $kostIds)->findOrFail($id);
        $kamar->delete();

        return redirect()->route('kamar.pengelola')->with('success', 'Kamar berhasil dihapus!');
    }
}