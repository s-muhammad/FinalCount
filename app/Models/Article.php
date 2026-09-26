<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar', 'title_en',
        'summary', 'summary_ar', 'summary_en',
        'body', 'body_ar', 'body_en',
        'image', 'slug', 'category', 'author', 'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function people(): MorphToMany
    {
        return $this->morphToMany(Person::class, 'personable');
    }

    public function translatable(): array
    {
        return ['title', 'summary', 'body'];
    }
}
