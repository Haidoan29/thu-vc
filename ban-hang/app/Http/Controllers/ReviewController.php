<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json(Review::all());
    }

    public function showProduct($id)
    {
        // Lấy sản phẩm theo id
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        // Lấy tất cả review của sản phẩm
        $reviews = Review::where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Truyền cả product và reviews vào view
        return view('user.productsDetail', compact('product', 'reviews'));
    }

    public function store(Request $request)
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return response()->json(['error' => 'not_logged_in']);
        }

        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        Review::create([
            'product_id' => $request->product_id,
            'user_id' => $userId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'user_name' => Session::get('user_name'), // hoặc auth()->user()->name
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
    }

    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        if (!$review) return response()->json(['message' => 'Review not found'], 404);

        $review->update([
            'product_id' => $request->product_id ?? $review->product_id,
            'user_id' => $request->user_id ?? $review->user_id,
            'rating' => $request->rating ?? $review->rating,
            'comment' => $request->comment ?? $review->comment
        ]);

        return response()->json($review);
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) return response()->json(['message' => 'Review not found'], 404);

        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
