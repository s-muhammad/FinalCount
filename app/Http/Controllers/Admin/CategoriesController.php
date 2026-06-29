<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Traits\ImageUploadTrait;

class CategoriesController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'description' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_in_menu' => 'nullable|boolean',
            'is_on_homepage' => 'nullable|boolean',
        ]);

        $category = new Category();

        $category->name = $request->name;
        $category->description = $request->description ?? [];
        $category->is_in_menu = $request->is_in_menu ?? false;
        $category->is_on_homepage = $request->is_on_homepage ?? false;

        if ($request->hasFile('image')) {
            $category->image = $this->uploadImage($request->file('image'),'uploads/category/');
        }

        $category->save();

        return redirect()->route('admin.categories.index');
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
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|array',
            'description' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_in_menu' => 'nullable|boolean',
            'is_on_homepage' => 'nullable|boolean',
        ]);

        $category->setTranslations('name', $request->name);
        $category->setTranslations('description', $request->description ?? []);
        $category->is_in_menu = $request->is_in_menu ?? false;
        $category->is_on_homepage = $request->is_on_homepage ?? false;

        $image = $category->image;
        if ($request->hasFile('image')) {
            File::delete($image);
            $category->image = $this->uploadImage($request->file('image'),'uploads/category/');
        }

        $category->save();

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image){
            File::delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index');
    }
}
