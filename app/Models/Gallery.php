<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'title', 'title_ar', 'title_en',
        'description', 'description_ar', 'description_en',
        'image', 'category', 'status',
    ];

    public function translatable(): array
    {
        return ['title', 'description'];
    }
}
