<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistSong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Song;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to be logged in.');
        }

        $playlists = Playlist::where('user_id', $user->id)->get();
        return view('myplaylist', compact('playlists'));
    }

    // Membuat playlist baru
    public function store(Request $request)
    {
        // Ambil pengguna dari request
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to be logged in.');
        }

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->user_id = Auth::id(); // Ganti auth()->id() dengan Auth::id()

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('playlists', 'public');
            $playlist->image = $imagePath;
        }

        $playlist->save();

        return redirect()->route('myplaylist')->with('success', 'Playlist created successfully!');
    }

    // Menambahkan lagu ke playlist
    public function addSong(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access your playlists.');
        }

        $request->validate([
            'song_id' => 'required|exists:songs,id',
            'playlist_id' => 'required|exists:playlists,id',
        ]);

        $playlist = Playlist::where('id', $request->playlist_id)
                            ->where('user_id', $user->id)
                            ->firstOrFail();

        $exists = PlaylistSong::where('playlist_id', $playlist->id)
                            ->where('song_id', $request->song_id)
                            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This song is already in the playlist.');
        }
        PlaylistSong::create([
            'playlist_id' => $playlist->id,
            'song_id' => $request->song_id,
        ]);

        return redirect()->back()->with('success', 'Song added to playlist!');
    }
    public function destroy(Request $request, Playlist $playlist)
    {
        $user = $request->user();

        if (!$user || $playlist->user_id !== $user->id) {
            return redirect()->route('myplaylist')->with('error', 'You are not authorized to delete this playlist.');
        }

        // Hapus playlist beserta relasi lagu-lagunya
        DB::transaction(function () use ($playlist) {
            $playlist->songs()->detach(); // Hapus semua relasi lagu
            $playlist->delete(); // Hapus playlist
        });

        return redirect()->route('myplaylist')->with('success', 'Playlist deleted successfully!');
    }
    public function removeSong(Request $request, Playlist $playlist, Song $song)
    {
        $user = $request->user();

        // Validasi bahwa user adalah pemilik playlist
        if (!$user || $playlist->user_id !== $user->id) {
            return redirect()->route('myplaylist')->with('error', 'You are not authorized to remove songs from this playlist.');
        }

        // Hapus relasi lagu dari playlist
        $playlist->songs()->detach($song->id);

        return redirect()->back()->with('success', 'Song removed from playlist successfully!');
    }
}
