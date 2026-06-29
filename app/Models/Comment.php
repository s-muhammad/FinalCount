<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['comment','name','blog_id','approved','email'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
