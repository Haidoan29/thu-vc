<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index() { return response()->json(Payment::all()); }

    public function show($id){
        $payment = Payment::find($id);
        if (!$payment) return response()->json(['message'=>'Payment not found'],404);
        return response()->json($payment);
    }

    public function store(Request $request){
        $request->validate([
            'order_id'=>'required|string',
            'payment_method'=>'required|string',
            'payment_status'=>'nullable|string',
            'transaction_id'=>'nullable|string',
            'amount'=>'nullable|numeric'
        ]);

        $payment = Payment::create([
            'order_id'=>$request->order_id,
            'payment_method'=>$request->payment_method,
            'payment_status'=>$request->payment_status ?? 'pending',
            'transaction_id'=>$request->transaction_id,
            'amount'=>$request->amount ?? 0
        ]);

        return response()->json($payment,201);
    }

    public function update(Request $request,$id){
        $payment = Payment::find($id);
        if (!$payment) return response()->json(['message'=>'Payment not found'],404);

        $payment->update([
            'order_id'=>$request->order_id ?? $payment->order_id,
            'payment_method'=>$request->payment_method ?? $payment->payment_method,
            'payment_status'=>$request->payment_status ?? $payment->payment_status,
            'transaction_id'=>$request->transaction_id ?? $payment->transaction_id,
            'amount'=>$request->amount ?? $payment->amount
        ]);

        return response()->json($payment);
    }

    public function destroy($id){
        $payment = Payment::find($id);
        if (!$payment) return response()->json(['message'=>'Payment not found'],404);

        $payment->delete();
        return response()->json(['message'=>'Payment deleted']);
    }
}
