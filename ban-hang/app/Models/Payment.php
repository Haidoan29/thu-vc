<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';

    protected $fillable = [
        'order_id', 'payment_method', 'payment_status', 'transaction_id', 'amount'
    ];
}
