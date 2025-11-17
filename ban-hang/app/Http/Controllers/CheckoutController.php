<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    // Lưu sản phẩm đã chọn vào session
    public function storeSession(Request $request)
    {
        $request->validate(['items' => 'required|array|min:1']);
        session(['checkout_items' => $request->items]);
        return response()->json(['status' => 'success']);
    }

    // Trang checkout
    public function index()
    {
        $checkoutItems = session('checkout_items', []);
        $items = collect($checkoutItems)->map(function ($item) {
            $product = Product::find($item['id']);
            return [
                'id' => $item['id'],
                'name' => $product->name ?? 'Sản phẩm',
                'image' => $product->image ?? '',
                'price' => $product->price ?? 0,
                'quantity' => $item['quantity'],
                'subtotal' => ($product->price ?? 0) * $item['quantity']
            ];
        });

        $totalAmount = $items->sum('subtotal');
        return view('user.checkout.index', compact('items', 'totalAmount'));
    }

    // Đặt hàng
    public function placeOrder(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required', // fullAddress
        ]);

        $checkoutItems = session('checkout_items', []);
        if (empty($checkoutItems)) return back()->with('error', 'Không có sản phẩm để đặt hàng!');

        $items = collect($checkoutItems)->map(function ($item) {
            $product = Product::find($item['id']);

            if ($product) {
                $product->stock = max(0, $product->stock - $item['quantity']);
                $product->save();
            }

            return [
                'id' => $item['id'],
                'name' => $product->name ?? 'Sản phẩm',
                'image' => $product->image ?? '',
                'price' => $product->price ?? 0,
                'quantity' => $item['quantity'],
                'subtotal' => ($product->price ?? 0) * $item['quantity']
            ];
        });

        $totalAmount = $items->sum('subtotal');

        $order = Order::create([
            'user_id' => session('user_id'),
            'items' => $items->toArray(),
            'shipping_address' => $request->address,
            'phone' => $request->phone,
            'total_amount' => $totalAmount,
            'payment_method' => 'COD',
            'status' => 'pending'
        ]);

        // Xóa sản phẩm đã mua khỏi giỏ hàng
        $cart = Cart::where('user_id', session('user_id'))->first();
        if ($cart) {
            $orderedIds = collect($checkoutItems)->pluck('id')->toArray();
            $cartItems = is_string($cart->items) ? json_decode($cart->items, true) : $cart->items;
            $cartItems = array_filter($cartItems, function ($quantity, $productId) use ($orderedIds) {
                return !in_array($productId, $orderedIds);
            }, ARRAY_FILTER_USE_BOTH);
            $cart->items = $cartItems;
            $cart->save();
        }

        session()->forget('checkout_items');

        return redirect('/')->with('success', 'Đặt hàng thành công!');
    }
    public function getDistricts($province_code)
    {
        try {
            $response = Http::get("https://provinces.open-api.vn/api/p/{$province_code}?depth=2");
            return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể lấy districts',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getWards($district_code)
    {
        try {
            $response = Http::get("https://provinces.open-api.vn/api/d/{$district_code}?depth=2");
            return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể lấy wards',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
