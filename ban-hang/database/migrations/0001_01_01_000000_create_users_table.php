<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('users', function (Blueprint $collection) {
            $collection->id();
            $collection->string('name');
            $collection->string('email')->unique();
            $collection->string('password'); // mật khẩu đã hash
            $collection->string('role')->default('customer'); // admin | customer
            $collection->string('phone')->nullable();
            $collection->string('address')->nullable();
            $collection->string('session_id')->nullable(); // lưu session hiện tại
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('users');
    }
};
