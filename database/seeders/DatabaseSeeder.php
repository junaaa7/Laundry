<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LaundryItem;
use App\Models\Price;
use App\Models\Bank;
use App\Models\TargetLaundry;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'address' => 'Jl. Admin No. 1, Kota',
                'role' => 'admin',
                'theme' => 'light'
            ]
        );
        
        // Create Karyawan User
        User::firstOrCreate(
            ['email' => 'karyawan@laundry.com'],
            [
                'name' => 'Karyawan Laundry',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'address' => 'Jl. Karyawan No. 1, Kota',
                'role' => 'karyawan',
                'theme' => 'light'
            ]
        );
        
        // Create Customer User
        User::firstOrCreate(
            ['email' => 'customer@laundry.com'],
            [
                'name' => 'Customer Test',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'address' => 'Jl. Customer No. 1, Kota',
                'role' => 'customer',
                'theme' => 'light'
            ]
        );
        
        // Create Laundry Items - MENGGUNAKAN firstOrCreate UNTUK MENCEGAH DUPLICATE
        $laundryItems = [
            ['name' => 'Kemeja', 'category' => 'pakaian', 'price_per_kg' => 8000, 'price_per_piece' => 5000, 'estimated_days' => 2, 'is_active' => true],
            ['name' => 'Celana', 'category' => 'pakaian', 'price_per_kg' => 8000, 'price_per_piece' => 6000, 'estimated_days' => 2, 'is_active' => true],
            ['name' => 'Jaket', 'category' => 'pakaian', 'price_per_kg' => 10000, 'price_per_piece' => 8000, 'estimated_days' => 2, 'is_active' => true],
            ['name' => 'Daster', 'category' => 'pakaian', 'price_per_kg' => 7000, 'price_per_piece' => 4000, 'estimated_days' => 2, 'is_active' => true],
            ['name' => 'Sepatu Sneakers', 'category' => 'sepatu', 'price_per_kg' => 15000, 'price_per_piece' => 15000, 'estimated_days' => 3, 'is_active' => true],
            ['name' => 'Sepatu Formal', 'category' => 'sepatu', 'price_per_kg' => 12000, 'price_per_piece' => 12000, 'estimated_days' => 3, 'is_active' => true],
            ['name' => 'Tas Sekolah', 'category' => 'tas', 'price_per_kg' => 10000, 'price_per_piece' => 10000, 'estimated_days' => 2, 'is_active' => true],
            ['name' => 'Tas Wanita', 'category' => 'tas', 'price_per_kg' => 12000, 'price_per_piece' => 12000, 'estimated_days' => 2, 'is_active' => true],
            ['name' => 'Selimut Kecil', 'category' => 'selimut', 'price_per_kg' => 10000, 'price_per_piece' => 15000, 'estimated_days' => 3, 'is_active' => true],
            ['name' => 'Selimut Besar', 'category' => 'selimut', 'price_per_kg' => 10000, 'price_per_piece' => 25000, 'estimated_days' => 3, 'is_active' => true],
        ];
        
        foreach ($laundryItems as $item) {
            LaundryItem::firstOrCreate(
                ['name' => $item['name'], 'category' => $item['category']],
                $item
            );
        }
        
        // Create Prices
        $prices = [
            ['category' => 'pakaian', 'type' => 'regular', 'price_per_kg' => 8000, 'minimum_price' => 15000, 'delivery_days' => 2, 'additional_fee' => 0, 'is_active' => true],
            ['category' => 'pakaian', 'type' => 'express', 'price_per_kg' => 12000, 'minimum_price' => 20000, 'delivery_days' => 1, 'additional_fee' => 5000, 'is_active' => true],
            ['category' => 'pakaian', 'type' => 'vip', 'price_per_kg' => 20000, 'minimum_price' => 30000, 'delivery_days' => 0, 'additional_fee' => 10000, 'is_active' => true],
            ['category' => 'sepatu', 'type' => 'regular', 'price_per_kg' => 15000, 'minimum_price' => 20000, 'delivery_days' => 2, 'additional_fee' => 0, 'is_active' => true],
            ['category' => 'tas', 'type' => 'regular', 'price_per_kg' => 12000, 'minimum_price' => 15000, 'delivery_days' => 2, 'additional_fee' => 0, 'is_active' => true],
            ['category' => 'selimut', 'type' => 'regular', 'price_per_kg' => 10000, 'minimum_price' => 20000, 'delivery_days' => 3, 'additional_fee' => 0, 'is_active' => true],
        ];
        
        foreach ($prices as $price) {
            Price::firstOrCreate(
                ['category' => $price['category'], 'type' => $price['type']],
                $price
            );
        }
        
        // Create Bank Accounts
        $banks = [
            ['bank_name' => 'BCA', 'account_number' => '1234567890', 'account_name' => 'Laundry Express', 'is_active' => true],
            ['bank_name' => 'Mandiri', 'account_number' => '9876543210', 'account_name' => 'Laundry Express', 'is_active' => true],
            ['bank_name' => 'BRI', 'account_number' => '5678901234', 'account_name' => 'Laundry Express', 'is_active' => true],
            ['bank_name' => 'BNI', 'account_number' => '3456789012', 'account_name' => 'Laundry Express', 'is_active' => true],
        ];
        
        foreach ($banks as $bank) {
            Bank::firstOrCreate(
                ['bank_name' => $bank['bank_name'], 'account_number' => $bank['account_number']],
                $bank
            );
        }
        
        $this->command->info('Database seeding completed successfully!');
    }
}