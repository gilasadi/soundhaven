<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use getID3;

class SongController extends Controller
{
    // Menampilkan semua lagu
    public function index(Request $request)
    {
        // Ambil query pencarian
        $search = $request->input('search');

        // Ambil semua lagu atau filter berdasarkan pencarian
        $songsQuery = Song::query();
        if ($search) {
            $songsQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('artist', 'like', '%' . $search . '%');
        }
        $songs = $songsQuery->get();

        // Ambil jumlah like untuk setiap song_id
        $likesCount = DB::table('likes')
                        ->select('song_id', DB::raw('count(*) as total_likes'))
                        ->groupBy('song_id')
                        ->pluck('total_likes', 'song_id');

        // Ambil top 5 lagu berdasarkan jumlah likes (hanya jika tidak sedang mencari)
        $topSongs = $search ? [] : Song::withCount(['likes as likes_count'])
                                    ->orderBy('likes_count', 'desc')
                                    ->take(5)
                                    ->get();

        // Ambil lagu terbaru (hanya jika tidak sedang mencari)
        $recentSongs = $search ? [] : Song::latest()->get();

        return view('all-music', compact('songs', 'likesCount', 'topSongs', 'recentSongs'));
    }


    // Menyimpan lagu baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png', // Validasi file gambar
            'file' => 'required|mimes:mp3', // Validasi file musik (MP3)
        ]);

        // Simpan file image
        $imagePath = $request->file('image')->store('images', 'public');

        // Simpan file musik
        $filePath = $request->file('file')->store('songs', 'public');

        // Mendapatkan durasi lagu menggunakan getID3
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze(storage_path('app/public/' . $filePath));
        $duration = isset($fileInfo['playtime_seconds']) ? round($fileInfo['playtime_seconds']) : null;

        // Buat record baru untuk lagu
        Song::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'image' => $imagePath,
            'file' => $filePath,
            'duration' => $duration, // Simpan durasi
        ]);

        return redirect()->route('all-music')->with('success', 'Song added successfully!');
    }

    public function toggleLike($id)
    {
        $userId = Auth::id();

        // Cek apakah user sudah memberikan like
        $like = DB::table('likes')->where('song_id', $id)->where('user_id', $userId)->first();

        if ($like) {
            // Jika sudah like, hapus like
            DB::table('likes')->where('id', $like->id)->delete();
            $message = 'Like removed!';
        } else {
            // Jika belum like, tambahkan like
            DB::table('likes')->insert([
                'song_id' => $id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $message = 'Like added!';
        }

        // Hitung total like terbaru
        $totalLikes = DB::table('likes')->where('song_id', $id)->count();

        return response()->json([
            'message' => $message,
            'likes' => $totalLikes,
        ]);
    }

    public function destroy($id)
    {
        // Pastikan pengguna yang login adalah admin
        $user = Auth::user(); // Ambil data user yang login
        if (!$user || $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Temukan lagu berdasarkan ID dan hapus
        $song = Song::findOrFail($id);
        $song->delete();

        return redirect()->route('all-music')->with('success', 'Song deleted successfully.');
    }

    public function topMusic()
    {
        // Ambil top 5 lagu berdasarkan jumlah like
        $topSongs = Song::withCount(['users as likes_count' => function ($query) {
            $query->where('liked', 1); // Hanya hitung like yang bernilai 1
        }])
            ->orderBy('likes_count', 'desc') // Urutkan berdasarkan likes
            ->take(5) // Ambil 5 lagu teratas
            ->get();

        // Kirim data ke view
        return view('topmusic', compact('topSongs'));
    }

    public function favoriteSongs()
    {
        $user = Auth::user();

        if ($user->role !== 'user') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $likedSongs = $user->songs; // Ambil semua lagu yang disukai
        return view('likedmusic', compact('likedSongs'));
    }
}

