<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    protected $fillable = ['name', 'description', 'image','is_in_menu','is_on_homepage'];

    public $translatable = ['name', 'description',];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
