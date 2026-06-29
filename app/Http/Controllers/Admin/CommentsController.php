<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    public function update(Comment $comment)
    {
        $comment->update([
            'approved' => 1
        ]);
        return redirect()->back();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index');
    }
}
