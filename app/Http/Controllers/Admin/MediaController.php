<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(10);
        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'file' => 'required|file|max:102400',
            'thumbnail' => 'nullable|image|max:2048',
            'type' => 'required|in:video,audio,image,document',
            'category' => 'required|in:speech,interview,documentary,other',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('media', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('media/thumbnails', 'public');
        }

        unset($validated['file']);

        Media::create($validated);

        return redirect()->route('admin.media.index')->with('success', 'رسانه با موفقیت ایجاد شد');
    }

    public function show(Media $medium)
    {
        return view('admin.media.show', ['media' => $medium]);
    }

    public function edit(Media $medium)
    {
        return view('admin.media.edit', ['media' => $medium]);
    }

    public function update(Request $request, Media $medium)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'file' => 'nullable|file|max:102400',
            'thumbnail' => 'nullable|image|max:2048',
            'type' => 'required|in:video,audio,image,document',
            'category' => 'required|in:speech,interview,documentary,other',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('media', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('media/thumbnails', 'public');
        }

        unset($validated['file']);

        $medium->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'رسانه با موفقیت بروزرسانی شد');
    }

    public function destroy(Media $medium)
    {
        if ($medium->thumbnail) {
            Storage::disk('public')->delete($medium->thumbnail);
        }

        if ($medium->file_path) {
            Storage::disk('public')->delete($medium->file_path);
        }

        $medium->delete();
        return redirect()->route('admin.media.index')->with('success', 'رسانه با موفقیت حذف شد');
    }
}
