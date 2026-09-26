<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $people = Person::orderBy('name')->get();

        return view('admin.messages.create', compact('people'));
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
            'type' => 'required|in:speech,message,decree',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'people_ids' => 'nullable|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);

        $validated['slug'] = Str::slug($request->title);

        unset($validated['people_ids']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('messages', 'public');
        }

        $message = Message::create($validated);
        $message->people()->sync($request->input('people_ids', []));

        return redirect()->route('admin.messages.index')->with('success', 'پیام با موفقیت ایجاد شد');
    }

    public function show(Message $message)
    {
        return view('admin.messages.show', compact('message'));
    }

    public function edit(Message $message)
    {
        $people = Person::orderBy('name')->get();
        $selectedPeople = $message->people;

        return view('admin.messages.edit', compact('message', 'people', 'selectedPeople'));
    }

    public function update(Request $request, Message $message)
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
            'type' => 'required|in:speech,message,decree',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'people_ids' => 'nullable|array',
            'people_ids.*' => 'integer|exists:people,id',
        ]);

        $validated['slug'] = Str::slug($request->title);

        unset($validated['people_ids']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('messages', 'public');
        }

        $message->update($validated);
        $message->people()->sync($request->input('people_ids', []));

        return redirect()->route('admin.messages.index')->with('success', 'پیام با موفقیت بروزرسانی شد');
    }

    public function destroy(Message $message)
    {
        if ($message->image) {
            Storage::disk('public')->delete($message->image);
        }

        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'پیام با موفقیت حذف شد');
    }
}
