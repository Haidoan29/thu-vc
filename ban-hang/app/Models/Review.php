<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
class Review extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'product_id', 'user_id', 'rating', 'comment'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
