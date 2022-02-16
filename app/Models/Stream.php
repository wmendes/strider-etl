<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_title',
        'user_email', 
        'size_mb',
        'start_at',
        'end_at'
        ];
}
