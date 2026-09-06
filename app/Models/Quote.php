<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'body', 'body_ar', 'body_en',
        'source', 'source_ar', 'source_en',
        'date', 'image', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translatable(): array
    {
        return ['body', 'source'];
    }
}
