<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // Auth untuk proses login/logout
use Illuminate\Support\Facades\Hash;    // Hash hanya dibutuhkan jika kamu ingin membandingkan manual

class LoginController extends Controller 
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }
    
    // Proses login
    public function login(Request $request)
    {
        // Validasi form login
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
        ]);
        
        // Set credentials dengan tambahan opsi untuk hash driver
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        // Coba autentikasi menggunakan Auth::attempt dengan pengaturan hash
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Hindari session fixation
            
            // Pengecekan khusus jika email adalah 'sofyanpriyaachmadi@gmail.com'
            if (Auth::user()->email === 'sofyanpriyaachmadi@gmail.com') {
                // Jika email sama, arahkan ke halaman admin
                return redirect()->route('admin.dashboard')->with('login_success', true);
            }
            
            // Check user role dan redirect sesuai dengan role-nya
            if (Auth::user()->role === 'admin') {
                // Redirect ke halaman admin
                return redirect()->route('admin.dashboard')->with('login_success', true);
            } else { 
                // Redirect ke halaman user jika bukan admin
                return redirect()->route('user.profile')->with('login_success', true);
            }
        }
        
        // Jika gagal
        
    
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();                         // Logout user
        $request->session()->invalidate();      // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token
        return redirect('/login');
    }
}