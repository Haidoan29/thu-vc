<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // public function index() { return response()->json(Order::all()); }
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search  = $request->input('search');
        $orders = Order::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {

                    // 1️⃣ Tìm theo user.name
                    // $query->whereHas('user', function ($u) use ($search) {
                    //     $u->where('name', 'like', "%{$search}%");
                    // });

                    // 2️⃣ Tìm theo phone (trong bảng orders)
                    $query->orWhere('phone', 'like', "%{$search}%");

                    // 3️⃣ Tìm theo địa chỉ
                    $query->orWhere('shipping_address', 'like', "%{$search}%");

                    // 4️⃣ Tìm theo trạng thái đơn hàng
                    $query->orWhere('status', 'like', "%{$search}%");

                    // 5️⃣ Tìm theo payment method
                    $query->orWhere('payment_method', 'like', "%{$search}%");

                    // 6️⃣ Tìm theo tổng tiền
                    if (is_numeric($search)) {
                        $query->orWhere('total_amount', $search);
                    }
                });
            })
            ->orderBy('_id', 'desc')
            ->paginate($perPage)
            ->appends($request->all()); // giữ lại search + perPage khi phân trang

        return view('admin.order.index', compact('orders', 'perPage'));
    }


    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'items' => 'required|array',
            'shipping_address' => 'nullable|string',
            'phone' => 'nullable|string',
            'total_amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'status' => 'nullable|string'
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'items' => $request->items,
            'shipping_address' => $request->shipping_address,
            'phone' => $request->phone,
            'total_amount' => $request->total_amount ?? 0,
            'payment_method' => $request->payment_method ?? 'cod',
            'status' => $request->status ?? 'pending'
        ]);

        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->update([
            'user_id' => $request->user_id ?? $order->user_id,
            'items' => $request->items ?? $order->items,
            'shipping_address' => $request->shipping_address ?? $order->shipping_address,
            'phone' => $request->phone ?? $order->phone,
            'total_amount' => $request->total_amount ?? $order->total_amount,
            'payment_method' => $request->payment_method ?? $order->payment_method,
            'status' => $request->status ?? $order->status
        ]);

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    public function myOrders()
    {
        // Lấy user_id từ session
        $userId = session('user_id');

        // Lấy tất cả đơn hàng của user
        $orders = Order::where('user_id', $userId)
            ->orderBy('_id', 'desc')
            ->get();

        return view('user.orders.my_orders', compact('orders'));
    }
}
