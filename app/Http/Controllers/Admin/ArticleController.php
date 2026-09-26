<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $people = Person::orderBy('name')->get();

        return view('admin.articles.create', compact('people'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'summary_ar' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'body' => 'nullable|string',
            'body_ar' => 'nullable|string',
            'body_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'people_ids' => 'nullable|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);

        $validated['slug'] = Str::slug($request->title);

        unset($validated['people_ids']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $article = Article::create($validated);
        $article->people()->sync($request->input('people_ids', []));

        return redirect()->route('admin.articles.index')->with('success', 'مقاله با موفقیت ایجاد شد');
    }

    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $people = Person::orderBy('name')->get();
        $selectedPeople = $article->people;

        return view('admin.articles.edit', compact('article', 'people', 'selectedPeople'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'summary_ar' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'body' => 'nullable|string',
            'body_ar' => 'nullable|string',
            'body_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'people_ids' => 'nullable|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);

        $validated['slug'] = Str::slug($request->title);

        unset($validated['people_ids']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($validated);
        $article->people()->sync($request->input('people_ids', []));

        return redirect()->route('admin.articles.index')->with('success', 'مقاله با موفقیت بروزرسانی شد');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'مقاله با موفقیت حذف شد');
    }
}
