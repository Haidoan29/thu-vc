<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectId;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Số lượng phân trang
        $perPage = $request->input('perPage', 15); // Mặc định 15
        $products = $query->paginate($perPage);
        // dd($query->get());
        return view('admin.products.index', compact('products', 'perPage'));
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
                $fileName = uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('products'), $fileName);
                $imageUrls[] = url('products/' . $fileName);
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
            'category_id' => $request->category_id
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

        $imageUrls = $product->images ?? [];

        // Nếu có ảnh mới — xoá toàn bộ ảnh cũ
        if ($request->hasFile('images')) {

            // XÓA ẢNH CŨ
            foreach ($product->images as $oldUrl) {

                // Tách file name từ URL
                $fileName = basename($oldUrl);

                // Xóa file trong public/products
                $oldPath = public_path('products/' . $fileName);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Lưu ảnh mới
            $imageUrls = [];
            foreach ($request->file('images') as $img) {
                $fileName = uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('products'), $fileName);
                $imageUrls[] = url('products/' . $fileName);
            }
        }

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'price'       => $request->price,
            'description' => $request->description,
            'stock'       => $request->stock,
            'status'      => $request->status,
            'images'      => $imageUrls,
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

        return view('user.products.category', compact('products', 'category'));
    }

    public function checkExist($id)
    {
        try {
            $product = Product::find(new ObjectId($id));
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false
            ]);
        }

        return response()->json([
            'exists' => $product && $product->stock > 0 && $product->status === 'active'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $perPage = 9; // 9 sản phẩm / trang

        $products = Product::query();

        if ($query !== '') {
            $products = $products->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        }

        // paginate và giữ query string để phân trang
        $products = $products->paginate($perPage)->withQueryString();

        // decode ảnh cho từng sản phẩm
        $products->getCollection()->transform(function ($product) {
            $images = $product->images;

            if (is_string($images)) {
                $images = json_decode($images, true);
            } elseif (!is_array($images)) {
                $images = [];
            }

            $product->images = $images ?? [];
            return $product;
        });

        return view('user.products.search', compact('products', 'query'));
    }
}
