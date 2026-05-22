<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaction;

return new class extends Migration
{
    public function up()
    {
        // Update semua invoice number yang sudah ada
        $transactions = Transaction::all();
        foreach ($transactions as $transaction) {
            // Ganti slash dengan strip
            $newInvoice = str_replace('/', '-', $transaction->invoice_number);
            if ($transaction->invoice_number != $newInvoice) {
                $transaction->invoice_number = $newInvoice;
                $transaction->save();
            }
        }
    }

    public function down()
    {
        // Revert jika diperlukan
        $transactions = Transaction::all();
        foreach ($transactions as $transaction) {
            $oldInvoice = str_replace('-', '/', $transaction->invoice_number);
            if ($transaction->invoice_number != $oldInvoice) {
                $transaction->invoice_number = $oldInvoice;
                $transaction->save();
            }
        }
    }
};