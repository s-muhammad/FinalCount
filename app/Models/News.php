<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar', 'title_en',
        'summary', 'summary_ar', 'summary_en',
        'body', 'body_ar', 'body_en',
        'image', 'slug', 'is_featured', 'status', 'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function translatable(): array
    {
        return ['title', 'summary', 'body'];
    }
}
