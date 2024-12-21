<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['song_id', 'user_id', 'created_at', 'updated_at'];

    // Relasi ke Song
    public function song()
    {
        return $this->belongsTo(Song::class, 'song_id');
    }
}
