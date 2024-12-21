<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'artist', 'image', 'file', 'likes', 'duration'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'likes')->withPivot('liked')->withTimestamps();
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_songs');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'song_id');
    }
}

