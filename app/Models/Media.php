<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar', 'title_en',
        'description', 'description_ar', 'description_en',
        'file_path', 'thumbnail', 'type', 'category', 'is_featured', 'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function translatable(): array
    {
        return ['title', 'description'];
    }
}
