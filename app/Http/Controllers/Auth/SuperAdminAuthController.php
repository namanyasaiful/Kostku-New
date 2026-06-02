<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SuperAdminAuthController extends Controller
{
    public function view(){
        return view('pages.auth.superadmin.login-superadmin');
    }

    public function sessionLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role != 'superadmin') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun ini bukan Super Admin.',
                ]);
            }

            return redirect()->route('dashboard.superadmin');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
}
