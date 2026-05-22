<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionDetail;
use App\Models\LaundryItem;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting TransactionSeeder...');

        // Dapatkan customer pertama
        $customer = User::where('role', 'customer')->first();
        $karyawan = User::where('role', 'karyawan')->first();
        $laundryItem = LaundryItem::first();

        if (!$customer) {
            $this->command->error('Customer tidak ditemukan! Jalankan DatabaseSeeder dulu.');
            return;
        }

        if (!$karyawan) {
            $this->command->error('Karyawan tidak ditemukan! Jalankan DatabaseSeeder dulu.');
            return;
        }

        if (!$laundryItem) {
            $this->command->error('Laundry Item tidak ditemukan! Jalankan DatabaseSeeder dulu.');
            return;
        }

        // Cek apakah sudah ada transaksi
        $existingTransaction = Transaction::where('customer_id', $customer->id)->first();
        
        if ($existingTransaction) {
            $this->command->info('Transaksi sudah ada, tidak perlu membuat baru.');
            return;
        }

        // Buat transaksi contoh
        $transaction = new Transaction();
        $transaction->invoice_number = $transaction->generateInvoiceNumber();
        $transaction->customer_id = $customer->id;
        $transaction->user_id = $karyawan->id;
        $transaction->order_date = Carbon::now();
        $transaction->completion_date = Carbon::now()->addDays(2);
        $transaction->total_weight = 2.5;
        $transaction->total_price = 20000;
        $transaction->discount = 0;
        $transaction->tax = 2200;
        $transaction->grand_total = 22200;
        $transaction->paid_amount = 0;
        $transaction->change_amount = 0;
        $transaction->status = 'pending';
        $transaction->payment_status = 'unpaid';
        $transaction->payment_method = null;
        $transaction->notes = 'Transaksi contoh untuk testing';
        $transaction->save();

        // Buat detail transaksi
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'laundry_item_id' => $laundryItem->id,
            'quantity' => 1,
            'weight' => 2.5,
            'price' => 8000,
            'subtotal' => 20000
        ]);

        $this->command->info('Transaksi contoh berhasil dibuat!');
        $this->command->info('Invoice: ' . $transaction->invoice_number);
    }
}