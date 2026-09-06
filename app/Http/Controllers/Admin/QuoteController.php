<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::latest()->paginate(10);
        return view('admin.quotes.index', compact('quotes'));
    }

    public function create()
    {
        return view('admin.quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'body_ar' => 'nullable|string',
            'body_en' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'source_ar' => 'nullable|string|max:255',
            'source_en' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('quotes', 'public');
        }

        Quote::create($validated);

        return redirect()->route('admin.quotes.index')->with('success', 'نقل‌قول با موفقیت ایجاد شد');
    }

    public function show(Quote $quote)
    {
        return view('admin.quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        return view('admin.quotes.edit', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'body_ar' => 'nullable|string',
            'body_en' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'source_ar' => 'nullable|string|max:255',
            'source_en' => 'nullable|string|max:255',
            'date' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('quotes', 'public');
        }

        $quote->update($validated);

        return redirect()->route('admin.quotes.index')->with('success', 'نقل‌قول با موفقیت بروزرسانی شد');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('admin.quotes.index')->with('success', 'نقل‌قول با موفقیت حذف شد');
    }
}
