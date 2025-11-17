<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $categories = Category::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($perPage); // paginate() trả về LengthAwarePaginator

        return view('admin.categories.index', compact('categories', 'perPage'));
    }




    public function create()
    {
        $categories = Category::all(); // dùng cho select parent_id
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('categories'), $filename);
            $imageUrl = url('categories/' . $filename);
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'image' => $imageUrl
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) return redirect()->back()->with('error', 'Category not found');
        $categories = Category::where('_id', '!=', $id)->get(); // không chọn chính nó làm parent
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) return redirect()->back()->with('error', 'Category not found');

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('categories'), $filename);
            $data['image'] = url('categories/' . $filename);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
