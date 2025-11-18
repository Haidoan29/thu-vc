<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
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
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'payment_method' => 'required|in:COD,MOMO'
        ]);

        $checkoutItems = session('checkout_items', []);
        if (empty($checkoutItems)) {
            return back()->with('error', 'Không có sản phẩm để đặt hàng!');
        }

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
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);

        if ($request->payment_method === 'MOMO') {
            $momoService = new \App\Services\MomoService();
            $paymentResult = $momoService->createPaymentRequest($order->_id, $totalAmount, 'Thanh toan don hang ' . $order->_id);

            if ($paymentResult['success']) {
                Payment::create([
                    'order_id' => $order->_id,
                    'payment_method' => 'MOMO',
                    'payment_status' => 'pending',
                    'amount' => $totalAmount,
                    'transaction_id' => null
                ]);
                return redirect($paymentResult['payUrl']);
            } else {
                $order->delete();
                return back()->with('error', 'Không thể tạo thanh toán Momo: ' . $paymentResult['message']);
            }
        }

        // Nếu COD $cart = Cart::where('user_id', session('user_id'))->first();
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
        session()->forget('checkout_items'); // xóa session
        session()->save();
        return redirect('/')->with('success', 'Đặt hàng thành công!');
    }


    // Xử lý callback từ Momo
    public function momoReturn(Request $request)
    {

        $data = $request->all();

        // Tìm payment record
        $payment = Payment::where('order_id', $data['orderId'])->first();
        if (!$payment) {
            return redirect('/')->with('error', 'Không tìm thấy thông tin thanh toán');
        }

        // Cập nhật trạng thái payment
        if ($data['resultCode'] == 0) {
            $payment->update([
                'payment_status' => 'completed',
                'transaction_id' => $data['transId']
            ]);

            // Cập nhật trạng thái đơn hàng
            $order = Order::find($data['orderId']);
            if ($order) {
                $order->update(['status' => 'confirmed']);
                // Xóa sản phẩm khỏi giỏ hàng
                $this->clearCartItems($order);
            }

            return redirect('/')->with('success', 'Thanh toán thành công!');
        } else {
            $payment->update(['payment_status' => 'failed']);

            // Xóa đơn hàng nếu thanh toán thất bại
            $order = Order::find($data['orderId']);
            if ($order) {
                $order->delete();
            }

            return redirect('/')->with('error', 'Thanh toán thất bại!');
        }
    }

    // Xử lý IPN từ Momo
    public function momoNotify(Request $request)
    {

        $data = $request->all();

        $momoService = new \App\Services\MomoService();
        if (!$momoService->verifySignature($data)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }


        // Tìm và cập nhật payment
        $payment = Payment::where('order_id', $data['orderId'])->first();
        if ($payment) {
            if ($data['resultCode'] == 0) {
                $payment->update([
                    'payment_status' => 'completed',
                    'transaction_id' => $data['transId']
                ]);

                // Cập nhật đơn hàng
                $order = Order::find($data['orderId']);
                if ($order) {
                    $order->update(['status' => 'confirmed']);
                    $this->clearCartItems($order);
                }
            } else {
                $payment->update(['payment_status' => 'failed']);
            }
        } else {
        }

        return response()->json(['message' => 'OK']);
    }

    private function clearCartItems($order)
    {

        $cart = Cart::where('user_id', $order->user_id)->first();
        if ($cart) {
            $orderedIds = collect($order->items)->pluck('id')->toArray();
            $cartItems = is_string($cart->items) ? json_decode($cart->items, true) : $cart->items;
            $cartItems = array_filter($cartItems, function ($quantity, $productId) use ($orderedIds) {
                return !in_array($productId, $orderedIds);
            }, ARRAY_FILTER_USE_BOTH);
            $cart->items = $cartItems;
            $cart->save();
        }
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
