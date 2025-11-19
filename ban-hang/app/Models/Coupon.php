<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_value',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status'
    ];

    // Cast ngày tháng thành Carbon
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function isValid(): bool
    {
        $now = Carbon::now();
        return $this->status === 'active'
            && $now->between($this->start_date, $this->end_date)
            && $this->used_count < $this->usage_limit;
    }
}
