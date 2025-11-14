<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('carts', function (Blueprint $collection) {
            $collection->id();
            $collection->string('user_id');
            $collection->array('items')->nullable(); // array of { product_id, name, price, quantity }
            $collection->double('total_amount')->default(0);
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('carts');
    }
};
