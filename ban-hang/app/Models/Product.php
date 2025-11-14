<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name', 'slug', 'category_id', 'price', 'description', 'images', 'stock', 'status'
    ];

    protected $casts = [
        'images' => 'array',
    ];
    public function category()
    {
        // 'category_id' là field trong products
        // '_id' là primary key của bảng categories (nếu MongoDB) hoặc 'id' nếu MySQL
        return $this->belongsTo(Category::class, 'category_id', '_id');
    }
}
