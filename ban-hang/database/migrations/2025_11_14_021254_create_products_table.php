<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('products', function (Blueprint $collection) {
            $collection->id();
            $collection->string('name');
            $collection->string('slug')->unique();
            $collection->string('category_id')->nullable();
            $collection->double('price');
            $collection->text('description')->nullable();
            $collection->array('images')->nullable();
            $collection->integer('stock')->default(0);
            $collection->string('status')->default('active');
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('products');
    }
};
