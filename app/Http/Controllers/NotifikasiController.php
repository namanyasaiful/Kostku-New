<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return response()->json([
            'notifs' => $user->notifications()->latest()->take(15)->get(),
            'unread' => $user->unreadNotifications()->count(),
        ]);
    }

    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}