<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

class PengelolaAuthController extends Controller {
    public function viewRegister(){
        return view('pages.auth.pengelola.register-pengelola');
    }
    public function store(Request $request) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'telpon' => 'required|numeric',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'nama_kost' => 'required|string|max:255',
            'alamat_kost' => 'required|string|max:255',
            'sertifikat' => 'required|file|max:10240|mimes:pdf',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'telpon' => $request->telpon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'alamat' => $request->alamat,
            'role' => 'pengelola',
        ]);

        $path = $request->file('sertifikat')->store('sertifikat');

        // logic generate kode_kost
        $nama = $request->nama_kost;
        $awal   = substr($nama, 0, 1);
        $tengah = substr($nama, strlen($nama) / 2, 1);
        $akhir  = substr($nama, -1);
        $kode_kost = strtoupper($awal . $tengah . $akhir) . '-' . strtoupper(Str::random(4));

        $user->kosts()->create([
            'nama_kost' => $request->nama_kost,
            'alamat_kost' => $request->alamat_kost,
            'sertifikat' => $path,
            'kode_kost' => $kode_kost,
        ]);

        return redirect()->route('login')->withSuccess('Registration successful! You can now login!');
    }


        public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
