<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PeopleController extends Controller
{
    public function index()
    {
        $people = Person::withCount('articles', 'news', 'messages')
            ->latest()
            ->paginate(12);

        return view('admin.people.index', compact('people'));
    }

    public function create()
    {
        return view('admin.people.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePerson($request);
        $validated['slug'] = $this->uniqueSlug($request->name);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('people', 'public');
        }

        Person::create($validated);

        return redirect()->route('admin.people.index')->with('success', 'چهره با موفقیت اضافه شد.');
    }

    public function edit(Person $person)
    {
        return view('admin.people.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $validated = $this->validatePerson($request);

        if ($request->filled('slug') && $request->input('slug') !== $person->slug) {
            $validated['slug'] = $this->uniqueSlug($request->input('slug'));
        } else {
            $validated['slug'] = $person->slug;
        }

        if ($request->hasFile('image')) {
            if ($person->image) {
                Storage::disk('public')->delete($person->image);
            }

            $validated['image'] = $request->file('image')->store('people', 'public');
        }

        $person->update($validated);

        return redirect()->route('admin.people.index')->with('success', 'چهره با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Person $person)
    {
        if ($person->image) {
            Storage::disk('public')->delete($person->image);
        }

        $person->delete();

        return redirect()->route('admin.people.index')->with('success', 'چهره حذف شد.');
    }

    private function validatePerson(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'is_martyr' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_martyr'] = $request->boolean('is_martyr');
        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }

    private function uniqueSlug(string $name): string
    {
        $slug = Str::slug($name) ?: 'person-'.Str::lower(Str::random(6));
        $base = $slug;
        $i = 2;

        while (Person::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}