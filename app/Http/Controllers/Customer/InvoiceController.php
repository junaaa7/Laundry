<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function download(Transaction $transaction)
    {
        // Pastikan transaksi milik customer yang login
        if ($transaction->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $transaction->load(['customer', 'details.laundryItem']);
        
        $filename = 'invoice_' . $transaction->invoice_number . '_' . Carbon::now()->format('Ymd_His') . '.pdf';
        $filename = str_replace('/', '-', $filename);
        
        $pdf = Pdf::loadView('customer.invoice', compact('transaction'));
        
        return $pdf->download($filename);
    }
}