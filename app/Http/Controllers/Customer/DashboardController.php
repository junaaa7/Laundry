<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\LaundryItem;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $recentTransactions = Transaction::where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $activeOrders = Transaction::where('customer_id', $user->id)
            ->whereIn('status', ['pending', 'processing', 'washing', 'drying', 'ironing'])
            ->count();
        
        $completedOrders = Transaction::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $totalSpent = Transaction::where('customer_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        // Ambil kategori unik dari laundry items (tanpa double)
        $laundryTypes = LaundryItem::where('is_active', true)
            ->select('category')
            ->distinct()
            ->get()
            ->map(function($item) {
                // Ambil harga per kg untuk kategori tersebut
                $firstItem = LaundryItem::where('category', $item->category)
                    ->where('is_active', true)
                    ->first();
                $item->price_per_kg = $firstItem ? $firstItem->price_per_kg : 0;
                return $item;
            });
        
        return view('customer.dashboard', compact(
            'recentTransactions', 'activeOrders', 
            'completedOrders', 'totalSpent', 'laundryTypes'
        ));
    }
}