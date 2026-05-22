<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('transactions', 'pickup_date')) {
                $table->date('pickup_date')->nullable()->after('order_date');
            }
            if (!Schema::hasColumn('transactions', 'pickup_time')) {
                $table->string('pickup_time')->nullable()->after('pickup_date');
            }
            if (!Schema::hasColumn('transactions', 'pickup_address')) {
                $table->text('pickup_address')->nullable()->after('pickup_time');
            }
            if (!Schema::hasColumn('transactions', 'pickup_notes')) {
                $table->text('pickup_notes')->nullable()->after('pickup_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['pickup_date', 'pickup_time', 'pickup_address', 'pickup_notes']);
        });
    }
};