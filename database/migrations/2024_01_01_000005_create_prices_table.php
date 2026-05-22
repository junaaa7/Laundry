<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('type'); // regular, express, vip
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('minimum_price', 10, 2)->default(0);
            $table->integer('delivery_days');
            $table->decimal('additional_fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};