<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::latest()->paginate(10);
        return view('admin.gallery.index', compact('gallery'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'description' => 'nullable|string|max:500',
            'description_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'تصویر با موفقیت ایجاد شد');
    }

    public function show(Gallery $item)
    {
        return view('admin.gallery.show', ['item' => $item]);
    }

    public function edit(Gallery $item)
    {
        return view('admin.gallery.edit', ['item' => $item]);
    }

    public function update(Request $request, Gallery $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string|max:500',
            'description_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        $item->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'تصویر با موفقیت بروزرسانی شد');
    }

    public function destroy(Gallery $item)
    {
        $item->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'تصویر با موفقیت حذف شد');
    }
}
