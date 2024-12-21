<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'likes', 'user_id', 'song_id')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

}
