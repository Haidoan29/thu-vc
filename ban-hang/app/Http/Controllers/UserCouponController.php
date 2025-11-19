<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\UserCoupon;

class UserCouponController extends Controller
{
    public function available()
    {
        $userId = session('user_id');

        // Lấy danh sách coupon đang hoạt động
        $coupons = Coupon::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        // Lấy danh sách coupon mà user đã nhận
        $claimed = UserCoupon::where('user_id', $userId)
            ->pluck('coupon_id')
            ->toArray();

        return view('user.coupons.available', compact('coupons', 'claimed'));
    }


    // User nhận coupon
    public function claim($couponId)
    {
        $userId = session(key: 'user_id'); // hoặc auth()->id()
        $coupon = Coupon::find($couponId);

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
        }

        $exists = UserCoupon::where('user_id', $userId)
            ->where('coupon_id', $coupon->_id)
            ->exists();
        if ($exists) {
            return back()->with('error', 'Bạn đã nhận mã này rồi.');
        }

        UserCoupon::create([
            'user_id' => $userId,
            'coupon_id' => $coupon->_id,
            'used' => false,
            'assigned_at' => now()
        ]);

        return back()->with('success', 'Nhận mã giảm giá thành công!');
    }

    // Danh sách coupon user đã nhận
    public function index()
    {
        $userId = session('user_id');
        $userCoupons = UserCoupon::where('user_id', $userId)
            ->where('used', false)
            ->with('coupon')
            ->get()
            ->filter(fn($uc) => $uc->coupon && $uc->coupon->isValid());

        return view('user.coupons.index', compact('userCoupons'));
    }
}
