<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('reviews', function (Blueprint $collection) {
            $collection->id();
            $collection->string('product_id');
            $collection->string('user_id');
            $collection->integer('rating')->default(5);
            $collection->text('comment')->nullable();
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('reviews');
    }
};
