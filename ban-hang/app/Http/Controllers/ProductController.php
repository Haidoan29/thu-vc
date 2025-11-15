<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // gửi danh mục sang view
        return view('admin.products.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'stock'       => 'nullable|integer',
            'status'      => 'nullable|string',
            'images.*'    => 'nullable|image|max:2048'
        ]);

        $imageUrls = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $imageUrls[] = url(Storage::url($path));
            }
        }

        Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'price'       => $request->price,
            'description' => $request->description,
            'stock'       => $request->stock ?? 0,
            'status'      => $request->status ?? 'active',
            'images'      => $imageUrls,
            'category_id' => $request->category_id // thêm dòng này
        ]);


        return redirect()->route('admin.products.index')->with('success', 'Product created');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if (!$product) return back()->with('error', 'Không tìm thấy sản phẩm');

        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return back()->with('error', 'Product not found');

        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'stock'       => 'nullable|integer',
            'status'      => 'nullable|string',
            'images.*'    => 'nullable|image|max:2048'
        ]);

        $newImages = $product->images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $newImages[] = url(Storage::url($path));
            }
        }

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'price'       => $request->price,
            'description' => $request->description,
            'stock'       => $request->stock,
            'status'      => $request->status,
            'images'      => $newImages
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return back()->with('error', 'Product not found');

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted');
    }
    public function showByCategory($id)
    {
        $products = Product::where('category_id', $id)->get(); // giả sử bạn có field category_id
        $category = Category::find($id);

        return view('user.products.category', compact('products', 'category' ));
    }
}
