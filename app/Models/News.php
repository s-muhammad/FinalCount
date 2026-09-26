<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'title_ar', 'title_en',
        'summary', 'summary_ar', 'summary_en',
        'body', 'body_ar', 'body_en',
        'image', 'slug', 'is_featured', 'source_url', 'source_url_hash', 'status', 'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function people(): MorphToMany
    {
        return $this->morphToMany(Person::class, 'personable');
    }

    protected static function booted(): void
    {
        static::saved(function (News $news) {
            if ($news->is_featured) {
                static::query()
                    ->where('id', '!=', $news->id)
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }
        });
    }

    public function translatable(): array
    {
        return ['title', 'summary', 'body'];
    }
}
