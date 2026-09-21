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
        'status',
        'keywords',
        'error',
        'raw_title',
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