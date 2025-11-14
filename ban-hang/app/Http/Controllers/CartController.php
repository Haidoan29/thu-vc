<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index() { return response()->json(Cart::all()); }

    public function show($id){
        $cart = Cart::find($id);
        if (!$cart) return response()->json(['message'=>'Cart not found'],404);
        return response()->json($cart);
    }

    public function store(Request $request){
        $request->validate([
            'user_id'=>'required|string',
            'items'=>'nullable|array',
            'total_amount'=>'nullable|numeric'
        ]);

        $cart = Cart::create([
            'user_id'=>$request->user_id,
            'items'=>$request->items ?? [],
            'total_amount'=>$request->total_amount ?? 0
        ]);

        return response()->json($cart,201);
    }

    public function update(Request $request,$id){
        $cart = Cart::find($id);
        if (!$cart) return response()->json(['message'=>'Cart not found'],404);

        $cart->update([
            'user_id'=>$request->user_id ?? $cart->user_id,
            'items'=>$request->items ?? $cart->items,
            'total_amount'=>$request->total_amount ?? $cart->total_amount
        ]);

        return response()->json($cart);
    }

    public function destroy($id){
        $cart = Cart::find($id);
        if (!$cart) return response()->json(['message'=>'Cart not found'],404);

        $cart->delete();
        return response()->json(['message'=>'Cart deleted']);
    }
}
