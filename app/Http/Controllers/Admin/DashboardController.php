<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Message;
use App\Models\Media;
use App\Models\Article;
use App\Models\Interview;
use App\Models\Gallery;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'news' => News::count(),
            'messages' => Message::count(),
            'media' => Media::count(),
            'articles' => Article::count(),
            'interviews' => Interview::count(),
            'gallery' => Gallery::count(),
        ];

        $recentNews = News::latest()->take(5)->get();
        $recentMessages = Message::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentNews', 'recentMessages'));
    }
}
