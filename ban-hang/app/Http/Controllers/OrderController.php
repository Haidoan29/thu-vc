<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // public function index() { return response()->json(Order::all()); }
    public function index(){
        return view("admin/order/index");
    }

    public function show($id){
        $order = Order::find($id);
        if (!$order) return response()->json(['message'=>'Order not found'],404);
        return response()->json($order);
    }

    public function store(Request $request){
        $request->validate([
            'user_id'=>'required|string',
            'items'=>'required|array',
            'shipping_address'=>'nullable|string',
            'phone'=>'nullable|string',
            'total_amount'=>'nullable|numeric',
            'payment_method'=>'nullable|string',
            'status'=>'nullable|string'
        ]);

        $order = Order::create([
            'user_id'=>$request->user_id,
            'items'=>$request->items,
            'shipping_address'=>$request->shipping_address,
            'phone'=>$request->phone,
            'total_amount'=>$request->total_amount ?? 0,
            'payment_method'=>$request->payment_method ?? 'cod',
            'status'=>$request->status ?? 'pending'
        ]);

        return response()->json($order,201);
    }

    public function update(Request $request,$id){
        $order = Order::find($id);
        if (!$order) return response()->json(['message'=>'Order not found'],404);

        $order->update([
            'user_id'=>$request->user_id ?? $order->user_id,
            'items'=>$request->items ?? $order->items,
            'shipping_address'=>$request->shipping_address ?? $order->shipping_address,
            'phone'=>$request->phone ?? $order->phone,
            'total_amount'=>$request->total_amount ?? $order->total_amount,
            'payment_method'=>$request->payment_method ?? $order->payment_method,
            'status'=>$request->status ?? $order->status
        ]);

        return response()->json($order);
    }

    public function destroy($id){
        $order = Order::find($id);
        if (!$order) return response()->json(['message'=>'Order not found'],404);

        $order->delete();
        return response()->json(['message'=>'Order deleted']);
    }
}
