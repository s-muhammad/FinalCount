<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews = Interview::latest()->paginate(10);
        return view('admin.interviews.index', compact('interviews'));
    }

    public function create()
    {
        return view('admin.interviews.create');
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
            'video_url' => 'nullable|string|max:500',
            'guest_name' => 'nullable|string|max:255',
            'guest_name_ar' => 'nullable|string|max:255',
            'guest_name_en' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('interviews', 'public');
        }

        Interview::create($validated);

        return redirect()->route('admin.interviews.index')->with('success', 'گفتگو با موفقیت ایجاد شد');
    }

    public function show(Interview $interview)
    {
        return view('admin.interviews.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        return view('admin.interviews.edit', compact('interview'));
    }

    public function update(Request $request, Interview $interview)
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
            'video_url' => 'nullable|string|max:500',
            'guest_name' => 'nullable|string|max:255',
            'guest_name_ar' => 'nullable|string|max:255',
            'guest_name_en' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('interviews', 'public');
        }

        $interview->update($validated);

        return redirect()->route('admin.interviews.index')->with('success', 'گفتگو با موفقیت بروزرسانی شد');
    }

    public function destroy(Interview $interview)
    {
        $interview->delete();
        return redirect()->route('admin.interviews.index')->with('success', 'گفتگو با موفقیت حذف شد');
    }
}
