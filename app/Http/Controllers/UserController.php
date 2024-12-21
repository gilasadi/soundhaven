<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of all users except admin.
     */
    // public function index()
    // {
    //     // Ambil semua data user kecuali role 'admin'
    //     $users = User::where('role', '!=', 'admin')->get();

    //     return view('alluser', compact('users'));
    // }
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $users = User::where('role', 'user')->get();
        return view('alluser', compact('users'));
    }

    /**
     * Dummy method for deleting a user.
     */
    public function destroy($id)
    {
        // Cari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus pengguna
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();

        // Hapus gambar profil lama jika ada
        if ($user->profile_image && $user->profile_image !== 'images/profile.jpg') {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Simpan gambar profil baru
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $path;
        $user->save();

        return back()->with('success', 'Profile image updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $user = $request->user();
        if ($user->email === $request->email) {
            return back()->with('error', 'The new email cannot be the same as the current email.');
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Email updated successfully.');
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Password changed successfully. Please log in with your new password.');
    }

}
