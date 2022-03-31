<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;
    protected $fillable = [
        'genres_id',
        'name',
        'author',
        'short_desc',
        'book_preview_url',
        'book_url',
    ];
}
