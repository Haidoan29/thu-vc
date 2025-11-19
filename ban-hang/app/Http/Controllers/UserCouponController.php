<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCoupon;
use App\Models\Coupon;

class UserCouponController extends Controller
{
    // Lấy danh sách coupon còn hiệu lực cho user
    public function index(Request $request)
    {
        $userId = $request->user()->id; // hoặc session user_id
        $userCoupons = UserCoupon::where('user_id', $userId)
            ->where('used', false)
            ->with('coupon')
            ->get()
            ->filter(function($uc){
                return $uc->coupon && $uc->coupon->isValid();
            });

        return view('user.coupons.index', compact('userCoupons'));
    }
}
