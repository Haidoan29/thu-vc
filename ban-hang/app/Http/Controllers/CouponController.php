<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    // List tất cả coupon
    public function index()
    {
        $coupons = Coupon::orderBy('created_at','desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    // Form tạo coupon
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Lưu coupon mới
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required|unique:coupons,code',
            'discount_type'=>'required|in:percent,fixed',
            'discount_value'=>'required|numeric|min:1',
            'min_order_value'=>'required|numeric|min:0',
            'usage_limit'=>'required|integer|min:1',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
        ]);

        Coupon::create([
            'code'=>$request->code,
            'discount_type'=>$request->discount_type,
            'discount_value'=>$request->discount_value,
            'min_order_value'=>$request->min_order_value,
            'usage_limit'=>$request->usage_limit,
            'used_count'=>0,
            'start_date'=>new Carbon($request->start_date),
            'end_date'=>new Carbon($request->end_date),
            'status'=>'active',
        ]);

        return redirect()->route('admin.coupons.index')->with('success','Tạo coupon thành công.');
    }

    // Form edit coupon
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    // Cập nhật coupon
    public function update(Request $request, $id)
    {
        $request->validate([
            'code'=>'required|unique:coupons,code,'.$id,
            'discount_type'=>'required|in:percent,fixed',
            'discount_value'=>'required|numeric|min:1',
            'min_order_value'=>'required|numeric|min:0',
            'usage_limit'=>'required|integer|min:1',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'code'=>$request->code,
            'discount_type'=>$request->discount_type,
            'discount_value'=>$request->discount_value,
            'min_order_value'=>$request->min_order_value,
            'usage_limit'=>$request->usage_limit,
            'start_date'=>new Carbon($request->start_date),
            'end_date'=>new Carbon($request->end_date),
            'status'=>$request->status ?? 'active',
        ]);

        return redirect()->route('admin.coupons.index')->with('success','Cập nhật coupon thành công.');
    }

    // Xóa coupon
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success','Xóa coupon thành công.');
    }
}
