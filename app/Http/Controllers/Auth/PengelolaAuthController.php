<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class PengelolaAuthController extends Controller
{
    public function viewRegister()
    {
        return view('pages.auth.pengelola.register-pengelola');
    }

    public function store(Request $request) {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'telpon'      => 'required|numeric',
            'alamat'      => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email',
            'password'    => 'required|string|min:8',
            'nama_kost'   => 'required|string|max:255',
            'alamat_kost' => 'required|string|max:255',
            'sertifikat'  => 'required|file|max:10240|mimes:pdf',
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'telpon'   => $request->telpon,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'alamat'   => $request->alamat,
            'role'     => 'pengelola',
            'status'   => 'Menunggu',
        ]);

        $path = $request->file('sertifikat')->store('sertifikat', 'public');

        $user->kosts()->create([
            'nama_kost'   => $request->nama_kost,
            'alamat_kost' => $request->alamat_kost,
            'sertifikat'  => $path,
            'kode_kost'   => null,
        ]);

        return redirect()->route('register.pengelola')->with('registered', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}