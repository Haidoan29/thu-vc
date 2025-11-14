<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('payments', function (Blueprint $collection) {
            $collection->id();
            $collection->string('order_id');
            $collection->string('payment_method');
            $collection->string('payment_status')->default('pending');
            $collection->string('transaction_id')->nullable();
            $collection->double('amount')->default(0);
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('payments');
    }
};
