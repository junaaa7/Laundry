<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom order_date agar memiliki default current timestamp
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('order_date')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });
        
        // Update data yang sudah ada
        DB::table('transactions')->whereNull('order_date')->update(['order_date' => DB::raw('NOW()')]);
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('order_date')->change();
        });
    }
};