<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan halaman form login
    public function showLogin()
    {
        return view('login');
    }

    // 2. Memproses data login yang dikirim
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Cek apakah email dan password cocok dengan di database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Mencegah celah keamanan session fixation
            return redirect()->intended('/'); // Bawa masuk ke halaman utama/dashboard
        }

        // Jika salah, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ]);
    }

    // 3. Memproses proses keluar (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
