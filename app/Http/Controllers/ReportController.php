<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generatePDF()
    {
        // Ambil top 5 lagu berdasarkan jumlah like
        $topSongs = Song::withCount(['users as likes_count' => function ($query) {
            $query->where('liked', 1); // Hanya hitung like yang bernilai 1
        }])
            ->orderBy('likes_count', 'desc') // Urutkan berdasarkan likes
            ->take(5) // Ambil 5 lagu teratas
            ->get();

        // Ambil user saat ini
        $user = Auth::user();

        if ($user && $user->role === 'user') {
            // Untuk user, ambil playlist miliknya
            $playlists = Playlist::where('user_id', $user->id)->with('songs')->get();
        } else {
            // Untuk admin, tidak perlu ambil playlist
            $playlists = collect(); // Koleksi kosong
        }

        // Render view untuk PDF
        $pdf = Pdf::loadView('reports.topmusic', compact('topSongs', 'playlists', 'user'));

        // Unduh sebagai file PDF
        return $pdf->stream('top_music_report.pdf'); // Bisa diganti ->download() untuk langsung diunduh
    }
}
