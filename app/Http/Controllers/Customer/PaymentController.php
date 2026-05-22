<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $unpaidTransactions = Transaction::where('customer_id', $user->id)
            ->where('payment_status', '!=', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $banks = Bank::where('is_active', true)->get();
        
        return view('customer.payments.index', compact('unpaidTransactions', 'banks'));
    }

    public function process(Request $request, Transaction $transaction)
    {
        // Verify transaction belongs to user
        if ($transaction->customer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $rules = [
            'payment_method' => 'required|in:cash,transfer,qris',
        ];
        
        // Validasi: jika transfer atau qris, wajib upload bukti
        if ($request->payment_method === 'transfer' || $request->payment_method === 'qris') {
            $rules['payment_proof'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }
        
        $request->validate($rules);
        
        $data = [
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'paid_amount' => $transaction->grand_total,
            'change_amount' => 0
        ];
        
        if ($request->hasFile('payment_proof')) {
            // Hapus file lama jika ada
            if ($transaction->payment_proof && Storage::disk('public')->exists($transaction->payment_proof)) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }
            
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $data['payment_proof'] = $path;
        }
        
        $transaction->update($data);
        
        // Create notification for admin
        \App\Models\Notification::create([
            'user_id' => 1, // Admin
            'transaction_id' => $transaction->id,
            'title' => 'Pembayaran Laundry',
            'message' => "Customer {$transaction->customer->name} telah melakukan pembayaran untuk invoice {$transaction->invoice_number}",
            'type' => 'success',
            'channel' => 'web',
            'is_sent' => true,
            'sent_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil']);
    }
}