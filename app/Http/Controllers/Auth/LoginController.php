<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function view()
    {
        return view('pages.auth.login');
    }

    public function sessionLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Block pengelola yang belum aktif
            if ($user->role === 'pengelola' && $user->status !== 'Aktif') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum disetujui atau sedang dibatasi.',
                ]);
            }

            if ($user->role === 'penghuni') {
                return redirect()->route('dashboard.penghuni');
            } elseif ($user->role === 'pengelola') {
                return redirect()->route('dashboard.pengelola');
            } else {
                return redirect()->route('dashboard.superadmin');
            }
        }

        return back()->withErrors([
            'email' => 'Email or password is incorrect. Please try again!',
        ]);
    }
}