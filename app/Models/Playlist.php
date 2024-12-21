<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    // Relasi dengan User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Song (Many-to-Many)
    public function songs()
    {
        return $this->belongsToMany(Song::class, 'playlist_songs', 'playlist_id', 'song_id');
    }
}
