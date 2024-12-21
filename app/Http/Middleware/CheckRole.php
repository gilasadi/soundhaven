<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $userType): Response
    {

        if (Auth::check()) {

            if (Auth::user()->role == 'admin') {
                return $next($request);
            }


            if (Auth::user()->role == $userType) {
                return $next($request);
            }
        }

        if (Auth::check() && Auth::user()->role !== 'user') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return response()->json([
            'error' => 'Anda tidak memiliki izin untuk mengakses halaman ini',
            'userType' => $userType
        ], 403);
    }
}
