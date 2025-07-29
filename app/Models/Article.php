<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    // Colomn authoriser en écriture
    protected $fillable = [
        'title',
        'content',
        'published'
    ];

    protected $cast = [
        'published' => 'boolean', 
    ];
}
