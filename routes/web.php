<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Controllers\SongController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\CheckRole;

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/fitures', function () {
    return view('fitures');
})->name('fitures');

// Middleware untuk Authenticated User
Route::middleware(['auth'])->group(function () {

    Route::get('/myplaylist', function () {
        if (Auth::check() && Auth::user()->role !== 'user') {
            // Jika bukan user, arahkan ke halaman utama dengan pesan error
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Jika role adalah user, tampilkan halaman myplaylist
        return app(PlaylistController::class)->index(request());
    })->name('myplaylist');

    // Route::post('/playlists', function () {
    //     if (Auth::user()->role !== 'user') {
    //         return redirect('/')->with('error', 'Unauthorized access.');
    //     }
    //     return view('myplaylist');
    // })->name('playlists.store');

    // Hanya Admin yang dapat mengakses /all-user
    Route::get('/all-user', function () {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
        return (new UserController)->index();
    })->name('all-user');

    Route::delete('/users/{id}', function ($id) {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Jika admin, hapus user berdasarkan ID
        $controller = new UserController();
        return $controller->destroy($id);
    })->name('users.destroy');

    // Routes untuk Songs dan Likes
    Route::get('/all-music', [SongController::class, 'index'])->name('all-music');
    Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
    Route::post('/songs/{id}/like', [LikeController::class, 'toggleLike'])->name('songs.toggle-like');
    Route::delete('/songs/{id}', [SongController::class, 'destroy'])->name('songs.delete');

    // Routes untuk Playlist
    Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
    Route::post('/playlist/create', [PlaylistController::class, 'store'])->name('playlist.store');
    Route::post('/playlist/add-song', [PlaylistController::class, 'addSong'])->name('playlist.add-song');
    Route::get('/top-music', [SongController::class, 'topMusic'])->name('top-music');
    Route::get('/top-music/report', [ReportController::class, 'generatePDF'])->name('topmusic.report');
    Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
    Route::delete('/playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong'])->name('playlist.remove-song');

    // Routes untuk User Settings
    Route::get('/settings', function () {
        return view('settings'); // Pastikan file settings.blade.php ada di folder resources/views
    })->name('user.settings');
    Route::put('/settings/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::put('/settings/update-profile-image', [UserController::class, 'updateProfileImage'])->name('user.update-profile-image');
    Route::put('/settings/update-email', [UserController::class, 'updateEmail'])->name('user.update-email');

    Route::get('/myfavorit', [SongController::class, 'favoriteSongs'])->name('myfavorit');
    Route::post('/toggle-like/{id}', [LikeController::class, 'toggleLike'])->name('toggle-like');
});


