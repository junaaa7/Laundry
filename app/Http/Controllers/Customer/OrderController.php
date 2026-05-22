<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\LaundryItem;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.laundry_item_id' => 'required|exists:laundry_items,id',
            'items.*.service_type' => 'required|in:regular,express,vip',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.weight' => 'required|numeric|min:0.1',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required|string',
            'pickup_address' => 'required|string|min:5',
            'notes' => 'nullable|string'
        ]);

        // Price multiplier per service type
        $priceMultiplier = [
            'regular' => 1.00,
            'express' => 1.50,
            'vip' => 2.00
        ];
        
        $estimatedDays = [
            'regular' => 3,
            'express' => 2,
            'vip' => 1
        ];

        // Dapatkan karyawan
        $karyawan = User::where('role', 'karyawan')->first();
        if (!$karyawan) {
            $karyawan = User::where('role', 'admin')->first();
        }

        $totalPrice = 0;
        $totalWeight = 0;
        $itemsDetail = [];
        $fastestDays = 7;

        foreach ($request->items as $item) {
            $laundryItem = LaundryItem::find($item['laundry_item_id']);
            if (!$laundryItem) {
                continue;
            }
            
            $serviceType = $item['service_type']; // ambil service_type dari request
            $multiplier = $priceMultiplier[$serviceType];
            $pricePerKg = $laundryItem->price_per_kg * $multiplier;
            $weight = floatval($item['weight']);
            $quantity = intval($item['quantity']);
            
            $subtotal = $pricePerKg * $weight * $quantity;
            
            $totalPrice += $subtotal;
            $totalWeight += $weight * $quantity;
            
            // Update estimasi tercepat
            $days = $estimatedDays[$serviceType];
            if ($days < $fastestDays) {
                $fastestDays = $days;
            }
            
            $itemsDetail[] = [
                'laundry_item_id' => $item['laundry_item_id'],
                'service_type' => $serviceType, // PASTIKAN TERSIMPAN
                'quantity' => $quantity,
                'weight' => $weight,
                'price' => $pricePerKg,
                'subtotal' => $subtotal
            ];
        }
        
        $tax = $totalPrice * 0.11;
        $grandTotal = $totalPrice + $tax;
        
        $now = Carbon::now('Asia/Jakarta');
        $orderDate = $now->format('Y-m-d H:i:s');
        
        // Hitung completion date
        $completionDate = Carbon::parse($request->pickup_date)->addDays($fastestDays);
        
        $invoiceNumber = $this->generateInvoiceNumber();
        
        // Buat transaksi
        $transaction = new Transaction();
        $transaction->invoice_number = $invoiceNumber;
        $transaction->customer_id = auth()->id();
        $transaction->user_id = $karyawan->id;
        $transaction->order_date = $orderDate;
        $transaction->pickup_date = $request->pickup_date;
        $transaction->pickup_time = $request->pickup_time;
        $transaction->pickup_address = $request->pickup_address;
        $transaction->pickup_notes = $request->pickup_notes;
        $transaction->completion_date = $completionDate;
        $transaction->total_weight = $totalWeight;
        $transaction->total_price = $totalPrice;
        $transaction->discount = 0;
        $transaction->tax = $tax;
        $transaction->grand_total = $grandTotal;
        $transaction->paid_amount = 0;
        $transaction->change_amount = 0;
        $transaction->status = 'pending';
        $transaction->payment_status = 'unpaid';
        $transaction->notes = $request->notes;
        $transaction->save();

        // Simpan detail transaksi dengan service_type
        foreach ($itemsDetail as $detail) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'laundry_item_id' => $detail['laundry_item_id'],
                'service_type' => $detail['service_type'], // PASTIKAN INI TERSIMPAN
                'quantity' => $detail['quantity'],
                'weight' => $detail['weight'],
                'price' => $detail['price'],
                'subtotal' => $detail['subtotal']
            ]);
        }
        
        // Notifikasi
        Notification::create([
            'user_id' => $karyawan->id,
            'transaction_id' => $transaction->id,
            'title' => 'Order Laundry Baru',
            'message' => 'Customer ' . auth()->user()->name . ' telah membuat order baru dengan invoice ' . $invoiceNumber,
            'type' => 'success',
            'channel' => 'web',
            'is_sent' => true,
            'sent_at' => $orderDate
        ]);
        
        Notification::create([
            'user_id' => auth()->id(),
            'transaction_id' => $transaction->id,
            'title' => 'Order Berhasil Dibuat',
            'message' => 'Pesanan laundry Anda dengan invoice ' . $invoiceNumber . ' telah berhasil dibuat. Estimasi selesai: ' . $completionDate->format('d/m/Y'),
            'type' => 'success',
            'channel' => 'web',
            'is_sent' => true,
            'sent_at' => $orderDate
        ]);

        return redirect()->route('customer.payments')
            ->with('success', 'Pesanan berhasil dibuat! Estimasi selesai: ' . $completionDate->format('d/m/Y'));
    }
    
    private function generateInvoiceNumber()
    {
        $date = Carbon::now('Asia/Jakarta');
        $prefix = 'INV-' . $date->format('Ymd') . '-';
        
        $lastTransaction = Transaction::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $newNumber;
    }
}