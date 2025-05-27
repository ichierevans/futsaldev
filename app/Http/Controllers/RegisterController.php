<?php
// app/Http/Controllers/RegisterController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register'); // pastikan file resources/views/register.blade.php ada
    }

    public function register(Request $request)
    {
        return $this->store($request);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits_between:10,13',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
    
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }}