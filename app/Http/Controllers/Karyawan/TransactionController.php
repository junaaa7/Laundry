<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\LaundryItem;
use App\Models\Price;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $laundryItems = LaundryItem::where('is_active', true)->get();
        $prices = Price::where('is_active', true)->get();
        
        return view('karyawan.transactions.create', compact('customers', 'laundryItems', 'prices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.laundry_item_id' => 'required|exists:laundry_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.weight' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,qris'
        ]);

        $transaction = new Transaction();
        $transaction->invoice_number = $transaction->generateInvoiceNumber();
        $transaction->customer_id = $request->customer_id;
        $transaction->user_id = auth()->id();
        $transaction->order_date = Carbon::today();
        $transaction->status = 'pending';
        $transaction->payment_method = $request->payment_method;
        $transaction->notes = $request->notes;
        
        // Calculate totals
        $totalPrice = 0;
        $totalWeight = 0;
        
        foreach ($request->items as $item) {
            $laundryItem = LaundryItem::find($item['laundry_item_id']);
            $price = $item['weight'] 
                ? $laundryItem->price_per_kg * $item['weight']
                : $laundryItem->price_per_piece * $item['quantity'];
            
            $totalPrice += $price;
            $totalWeight += $item['weight'] ?? 0;
        }
        
        $transaction->total_weight = $totalWeight;
        $transaction->total_price = $totalPrice;
        $transaction->discount = $request->discount ?? 0;
        $transaction->tax = $totalPrice * 0.11; // 11% tax
        $transaction->grand_total = $totalPrice - $transaction->discount + $transaction->tax;
        $transaction->save();
        
        // Save transaction details
        foreach ($request->items as $item) {
            $laundryItem = LaundryItem::find($item['laundry_item_id']);
            $price = $item['weight'] 
                ? $laundryItem->price_per_kg * $item['weight']
                : $laundryItem->price_per_piece * $item['quantity'];
            
            $transaction->details()->create([
                'laundry_item_id' => $item['laundry_item_id'],
                'quantity' => $item['quantity'],
                'weight' => $item['weight'] ?? null,
                'price' => $item['weight'] ? $laundryItem->price_per_kg : $laundryItem->price_per_piece,
                'subtotal' => $price
            ]);
        }
        
        // Create notification for customer
        \App\Models\Notification::create([
            'user_id' => $request->customer_id,
            'transaction_id' => $transaction->id,
            'title' => 'Transaksi Laundry Baru',
            'message' => "Transaksi laundry dengan invoice {$transaction->invoice_number} telah dibuat",
            'type' => 'success',
            'channel' => 'web',
            'is_sent' => true,
            'sent_at' => now()
        ]);

        return redirect()->route('karyawan.orders.index')->with('success', 'Transaksi berhasil dibuat');
    }

    public function processPayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0'
        ]);
        
        $change = $request->paid_amount - $transaction->grand_total;
        
        $transaction->update([
            'paid_amount' => $request->paid_amount,
            'change_amount' => $change > 0 ? $change : 0,
            'payment_status' => $request->paid_amount >= $transaction->grand_total ? 'paid' : 'partial'
        ]);
        
        return redirect()->back()->with('success', 'Pembayaran berhasil diproses');
    }
}