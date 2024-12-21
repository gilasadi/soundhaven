<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Menetapkan gambar default
        $defaultProfileImage = 'images/profile.jpg'; // File di folder public/images

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role sebagai user
            'profile_image' => $defaultProfileImage,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil!');
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Arahkan ke halaman All Music
            return redirect('/all-music');
        }

        // Jika login gagal
        return back()->withErrors(['login' => 'Email atau password salah']);
    }

    public function dashboard()
    {
        // Pastikan user terautentikasi
        if (Auth::check()) {
            // Data pengguna yang sedang login
            $user = Auth::user();
            return view('user', ['user' => $user]);
        }

        // Jika tidak terautentikasi, arahkan ke login
        return redirect('/login');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
