<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $userId = session('user_id');

        if (!$userId) {
            return response()->json(['error' => 'not_logged_in'], 401);
        }

        $productId = $request->product_id;
        $quantity  = $request->quantity;

        // Lấy giỏ hàng theo user
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId],
            ['items' => [], 'total_amount' => 0]
        );

        $items = $cart->items;

        // Nếu đã có sản phẩm thì tăng số lượng
        if (isset($items[$productId])) {
            $items[$productId] += $quantity;
        } else {
            $items[$productId] = $quantity;
        }

        // Cập nhật lại giỏ hàng
        $cart->items = $items;
        $cart->save();

        // Tổng số lượng để hiển thị lên header
        $cartCount = array_sum($items);

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount
        ]);
    }

    public function index()
    {
        $userId = session('user_id');
        $cart = Cart::where('user_id', $userId)->first();

        $items = [];
        $totalAmount = 0;

        if ($cart && !empty($cart->items)) {
            $cartItems = $cart->items; // đã là array ['product_id' => quantity]

            $productIds = array_keys($cartItems);
            $products = Product::whereIn('_id', $productIds)->get()->keyBy('_id');

            foreach ($cartItems as $productId => $quantity) {
                if (isset($products[$productId])) {
                    $product = $products[$productId];
                    $subtotal = $product->price * $quantity;
                    $totalAmount += $subtotal;

                    $items[] = [
                        'id' => $product->_id,
                        'name' => $product->name,
                        'image' => $product->image,
                        'price' => $product->price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }


        return view('user.cart.index', [
            'items' => $items,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function getCart()
    {
        $userId = session('user_id');

        $cart = Cart::where('user_id', $userId)->first();

        return response()->json([
            'items' => $cart ? $cart->items : []
        ]);
    }
    public function getItems()
    {
        $userId = session('user_id');

        if (!$userId) {
            return response()->json([
                'items' => [],
                'total' => 0
            ]);
        }

        $cart = \App\Models\Cart::where('user_id', $userId)->first();

        if (!$cart || empty($cart->items)) {
            return response()->json([
                'items' => [],
                'total' => 0
            ]);
        }

        $items = $cart->items;
        $productIds = array_keys($items);

        // Lấy sản phẩm bằng Product model
        $products = Product::whereIn('_id', $productIds)->get();

        $cartItems = [];
        $total = 0;

        foreach ($products as $p) {
            $quantity = $items[$p->_id] ?? 0;
            $price = $p->price ?? 0;

            $cartItems[] = [
                'id' => $p->_id,
                'name' => $p->name,
                'image' => $p->images[0] ?? 'https://via.placeholder.com/60',
                'price' => $price,
                'quantity' => $quantity
            ];

            $total += $price * $quantity;
        }

        return response()->json([
            'items' => $cartItems,
            'total' => $total
        ]);
    }
    public function updateQuantity(Request $request)
    {
        $userId = session('user_id');
        $productId = $request->input('product_id');
        $quantity = intval($request->input('quantity', 1));

        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) return response()->json(['status' => 'error', 'message' => 'Giỏ hàng trống']);

        $items = $cart->items; // Nếu bạn đang cast items sang array
        $items[$productId] = $quantity; // Cập nhật số lượng
        $cart->items = $items;
        $cart->save();

        return response()->json(['status' => 'success']);
    }
}
