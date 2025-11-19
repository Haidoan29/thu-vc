<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class UserCoupon extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'user_coupons';

    protected $fillable = [
        'user_id',       // id user
        'coupon_id',     // id coupon
        'used',          // true/false
        'assigned_at',   // ngày được cấp
        'used_at',       // ngày dùng
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
