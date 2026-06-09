<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\RequestMasukNotification;

class PenghuniAuthController extends Controller
{
    public function viewRegister(){
        return view('pages.auth.penghuni.register-penghuni');
    }

    public function store(Request $request){
        $request->validate([
            'nama'     => 'required|string|max:255',
            'telpon'   => 'required|numeric',
            'alamat'   => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ], [
            'nama.required'     => 'Nama wajib diisi.',
            'telpon.required'   => 'Nomor telepon wajib diisi.',
            'telpon.numeric'    => 'Nomor telepon harus berupa angka.',
            'alamat.required'   => 'Alamat wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        $penghuni = User::create([
            'nama'     => $request->nama,
            'telpon'   => $request->telpon,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'alamat'   => $request->alamat,
            'role'     => 'penghuni',
            'status'   => 'Aktif',
        ]);

        return redirect()->route('login')->withSuccess('Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
