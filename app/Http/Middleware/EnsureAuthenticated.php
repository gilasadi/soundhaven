<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class EnsureAuthenticated
// {
//     public function handle(Request $request, Closure $next)
//     {
//         if (!Auth::check()) {
//             // Redirect ke halaman login jika belum login
//             return redirect('/login')->with('error', 'You must be logged in to access this page.');
//         }

//         $user = Auth::user();
//         if ($user->role !== 'admin') {
//             // Jika bukan admin, redirect ke halaman lain
//             return redirect('/')->with('error', 'Unauthorized access.');
//         }
//         return $next($request);
//     }
// }
