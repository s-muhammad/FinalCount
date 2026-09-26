<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'is_martyr',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_martyr' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'personable');
    }

    public function news(): MorphToMany
    {
        return $this->morphedByMany(News::class, 'personable');
    }

    public function messages(): MorphToMany
    {
        return $this->morphedByMany(Message::class, 'personable');
    }
}