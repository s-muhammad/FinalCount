<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RssFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'language',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function imports(): HasMany
    {
        return $this->hasMany(RssImport::class, 'feed_id');
    }
}