<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SuperAdminAuthController extends Controller
{
    public function view()
    {
        return view('pages.auth.superadmin.login-superadmin');
    }

    public function sessionLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        if (Auth::user()->role !== 'super_admin') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun ini bukan Super Admin.',
            ]);
        }

        return redirect()->route('dashboard.superadmin');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }
}