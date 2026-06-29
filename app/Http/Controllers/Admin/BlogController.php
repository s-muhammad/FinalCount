<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\Tags\Tag;
use App\Traits\ImageUploadTrait;

class BlogController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blog.index',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.blog.create',compact('tags','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|array',
            'summary' => 'required|array',
            'body' => 'required|array',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->summary = $request->summary;
        $blog->body = $request->body;
        $blog->image = $this->uploadImage($request->file('image'),'uploads/blog/');
        $blog->category_id = $request->category_id;
        $blog->save();

        if ($request->has('tags')) {
            foreach ($request->tags as $locale => $localeTags) {
                if (!empty($localeTags)) {
                    $tagModels = [];

                    foreach ($localeTags as $tagName) {
                        $tagModels[] = Tag::findOrCreate($tagName, $locale, $locale);
                    }

                    $blog->attachTags($tagModels, $locale);
                }
            }
        }

        return redirect()->route('admin.blog.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        $faTags = Tag::getWithType('fa');
        $enTags = Tag::getWithType('en');
        $arTags = Tag::getWithType('ar');
        $categories = Category::all();

        return view('admin.blog.edit', compact('blog', 'faTags', 'enTags', 'arTags','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|array',
            'summary' => 'required|array',
            'body' => 'required|array',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $blog->title = $request->input('title');
        $blog->summary = $request->input('summary');
        $blog->body = $request->input('body');
        $blog->category_id = $request->category_id;
        if ($request->hasFile('image')) {
            if (!empty($blog->image) && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }

            $blog->image = $this->uploadImage($request->file('image'),'uploads/blog/');
        }

        $blog->save();

        $locales = ['fa', 'en', 'ar'];
        foreach ($locales as $locale) {
            $localeTags = $request->input("tags.$locale", []);
            $tagModels = [];

            if (!empty($localeTags)) {
                // ۲. پاک‌سازی آرایه از مقادیر null یا خالی (بسیار مهم)
                $cleanTags = array_filter($localeTags, function($value) {
                    return !is_null($value) && trim($value) !== '';
                });
                foreach ($cleanTags as $tagName) {
                    $tagModels[] = Tag::findOrCreate(trim($tagName), $locale, $locale);
                }
            }
            $blog->syncTagsWithType($tagModels, $locale);
        }

        return redirect()->route('admin.blog.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->image)
        {
            File::delete($blog->image);
        }
        $blog->delete();
        return redirect()->route('admin.blog.index');
    }
}
