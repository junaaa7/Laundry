<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download(Transaction $transaction)
    {
        // Verify transaction belongs to user
        if ($transaction->customer_id !== auth()->id()) {
            abort(403);
        }
        
        $transaction->load(['details.laundryItem']);
        
        $pdf = Pdf::loadView('customer.receipts.struk', compact('transaction'));
        return $pdf->download('struk-' . $transaction->invoice_number . '.pdf');
    }
}