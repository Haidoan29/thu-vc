<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mongodb')->create('categories', function (Blueprint $collection) {
            $collection->id();
            $collection->string('name');
            $collection->string('slug')->unique();
            $collection->text('description')->nullable();
            $collection->string('parent_id')->nullable();
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('categories');
    }
};
