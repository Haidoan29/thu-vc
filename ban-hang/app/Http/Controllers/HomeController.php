<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $products = Product::latest()->take(9)->get();
        $getproducts = Product::latest()->take(24)->get();
        $latestProducts = Product::orderBy('created_at', 'desc')
            ->take(8) 
            ->get();
        return view('home', compact('categories', 'products' , 'latestProducts' , 'getproducts'));
    }
    public function ProductsDetail($id)
    {
        $product = Product::findOrFail($id);
        return view('user.productsDetail', compact('product'));
    }
}
