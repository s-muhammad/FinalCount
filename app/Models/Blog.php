<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{
    use HasTranslations;
    use HasTags;
    protected $fillable = ['title', 'body', 'summary','image','category_id'];

    public $translatable = ['title', 'body', 'summary'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * محاسبه زمان تقریبی مطالعه
     */
    public function getReadingTimeAttribute()
    {
        // ۱. اگر متن خالی بود، 1 دقیقه برگردان
        if (!$this->body) {
            return 1;
        }

        // ۲. حذف کدهای HTML از متن (تا تگ‌ها به عنوان کلمه شمرده نشوند)
        $cleanText = strip_tags($this->body);

        // ۳. شمارش دقیق کلمات با پشتیبانی کامل از زبان‌های فارسی، عربی و انگلیسی (UTF-8)
        $wordCount = count(preg_split('~[^\p{L}\p{N}\']+~u', $cleanText));

        // ۴. سرعت متوسط خواندن (200 کلمه در دقیقه)
        $wordsPerMinute = 200;

        // ۵. محاسبه زمان و گرد کردن آن به سمت بالا (مثلا 1.2 دقیقه می‌شود 2 دقیقه)
        $minutes = ceil($wordCount / $wordsPerMinute);

        // حداقل زمان مطالعه را 1 دقیقه در نظر می‌گیریم
        return $minutes > 0 ? $minutes : 1;
    }
}
