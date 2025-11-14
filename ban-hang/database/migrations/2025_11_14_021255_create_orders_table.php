<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('orders', function (Blueprint $collection) {
            $collection->id();
            $collection->string('user_id');
            $collection->array('items'); // array of { product_id, name, price, quantity }
            $collection->string('shipping_address')->nullable();
            $collection->string('phone')->nullable();
            $collection->double('total_amount')->default(0);
            $collection->string('payment_method')->default('cod'); // cod | online
            $collection->string('status')->default('pending'); // pending | confirmed | shipped | completed | cancelled
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('orders');
    }
};
