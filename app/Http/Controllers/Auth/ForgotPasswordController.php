<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    // 1. Fungsi untuk papar borang minta emel
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Fungsi untuk hantar 'link' (Simulasi)
    public function sendResetLinkEmail(Request $request)
    {
        // Pastikan pengguna masukkan format emel yang betul
        $request->validate([
            'email' => 'required|email'
        ]);

        // (Untuk sistem sebenar, di sini kita letak kod hantar emel)
        // Buat masa ini, kita buat simulasi berjaya hantar supaya sistem tidak ralat

        return back()->with('status', 'Pautan tetapan semula kata laluan telah dihantar ke emel anda!');
    }
}
