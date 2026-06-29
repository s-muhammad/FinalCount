<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $users = User::count();
        $comments = Comment::latest()->limit(5)->get();
        $blogs = Blog::latest()->take(5)->get();
        return view('admin.index',compact('comments','users','blogs'));    }
}
