<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id', 'items', 'shipping_address', 'phone',
        'total_amount', 'payment_method', 'status'
    ];

    protected $casts = [
        'items' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}
