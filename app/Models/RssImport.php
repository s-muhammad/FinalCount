<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RssImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'news_id',
        'source_url',
        'source_url_hash',
        'status',
        'keywords',
        'error',
        'raw_title',
        'raw_body',
        'image',
        'title',
        'title_ar',
        'title_en',
        'summary',
        'summary_ar',
        'summary_en',
        'body',
        'body_ar',
        'body_en',
        'published_as',
        'imported_at',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
    ];

    public function feed(): BelongsTo
    {
        return $this->belongsTo(RssFeed::class, 'feed_id');
    }

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}