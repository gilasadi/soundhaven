<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $id)
    {
        $song = Song::find($id);
        if (!$song) {
            return response()->json(['message' => 'Song not found'], 404);
        }

        $user = Auth::user();
        if ($song->users->contains($user->id)) {
            $song->users()->detach($user->id); // Batalkan like
            $liked = false;
        } else {
            $song->users()->attach($user->id); // Tambahkan like
            $liked = true;
        }

        $likeCount = $song->users()->count();

        return response()->json([
            'message' => $liked ? 'Like added' : 'Like removed',
            'likes' => $likeCount,
            'liked' => $liked,
        ]);
    }

}



