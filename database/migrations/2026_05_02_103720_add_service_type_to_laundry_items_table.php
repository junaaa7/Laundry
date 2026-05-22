<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laundry_items', function (Blueprint $table) {
            if (!Schema::hasColumn('laundry_items', 'service_type')) {
                $table->enum('service_type', ['regular', 'express', 'vip'])->default('regular')->after('category');
            }
            if (!Schema::hasColumn('laundry_items', 'price_multiplier')) {
                $table->decimal('price_multiplier', 3, 2)->default(1.00)->after('price_per_kg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laundry_items', function (Blueprint $table) {
            $table->dropColumn(['service_type', 'price_multiplier']);
        });
    }
};