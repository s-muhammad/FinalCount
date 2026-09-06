<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar', 'title_en',
        'summary', 'summary_ar', 'summary_en',
        'body', 'body_ar', 'body_en',
        'image', 'video_url',
        'guest_name', 'guest_name_ar', 'guest_name_en',
        'slug', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function translatable(): array
    {
        return ['title', 'summary', 'body', 'guest_name'];
    }
}
