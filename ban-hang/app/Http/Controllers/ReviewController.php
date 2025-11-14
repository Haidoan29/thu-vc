<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index() { return response()->json(Review::all()); }

    public function show($id){
        $review = Review::find($id);
        if (!$review) return response()->json(['message'=>'Review not found'],404);
        return response()->json($review);
    }

    public function store(Request $request){
        $request->validate([
            'product_id'=>'required|string',
            'user_id'=>'required|string',
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'nullable|string'
        ]);

        $review = Review::create([
            'product_id'=>$request->product_id,
            'user_id'=>$request->user_id,
            'rating'=>$request->rating,
            'comment'=>$request->comment
        ]);

        return response()->json($review,201);
    }

    public function update(Request $request,$id){
        $review = Review::find($id);
        if (!$review) return response()->json(['message'=>'Review not found'],404);

        $review->update([
            'product_id'=>$request->product_id ?? $review->product_id,
            'user_id'=>$request->user_id ?? $review->user_id,
            'rating'=>$request->rating ?? $review->rating,
            'comment'=>$request->comment ?? $review->comment
        ]);

        return response()->json($review);
    }

    public function destroy($id){
        $review = Review::find($id);
        if (!$review) return response()->json(['message'=>'Review not found'],404);

        $review->delete();
        return response()->json(['message'=>'Review deleted']);
    }
}
