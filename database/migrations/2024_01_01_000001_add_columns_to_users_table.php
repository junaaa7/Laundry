<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'karyawan', 'customer'])->default('customer')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('address');
            $table->enum('theme', ['light', 'dark'])->default('light')->after('profile_photo');
            $table->boolean('telegram_notification')->default(false)->after('theme');
            $table->boolean('whatsapp_notification')->default(false)->after('telegram_notification');
            $table->boolean('email_notification')->default(true)->after('whatsapp_notification');
            $table->string('telegram_chat_id')->nullable()->after('email_notification');
            $table->string('whatsapp_number')->nullable()->after('telegram_chat_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'address', 'profile_photo', 'theme',
                'telegram_notification', 'whatsapp_notification', 'email_notification',
                'telegram_chat_id', 'whatsapp_number'
            ]);
        });
    }
};