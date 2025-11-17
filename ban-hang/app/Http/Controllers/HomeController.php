<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $products = Product::latest()->take(3)->get();
        $getproducts = Product::latest()->take(12)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        return view('home', compact('categories', 'products', 'latestProducts', 'getproducts'));
    }
    public function ProductsDetail($id)
    {
        $product = Product::findOrFail($id);

        $reviews = Review::with('user') // lấy luôn thông tin user
            ->where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        $averageRating = $reviews->count() ? $reviews->avg('rating') : 0;
        // dd($averageRating);
        return view('user.productsDetail', compact('product', 'reviews', 'averageRating'));
    }
}
