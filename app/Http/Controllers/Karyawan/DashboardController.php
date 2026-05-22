<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Today's stats
        $todayOrders = Transaction::whereDate('order_date', $today)->count();
        $todayCompleted = Transaction::whereDate('completion_date', $today)
            ->where('status', 'completed')
            ->count();
        
        // Pending orders
        $pendingOrders = Transaction::where('status', 'pending')->count();
        $processingOrders = Transaction::whereIn('status', ['processing', 'washing', 'drying', 'ironing'])->count();
        
        // Recent orders
        $recentOrders = Transaction::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Orders by status
        $ordersByStatus = Transaction::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();
        
        return view('karyawan.dashboard', compact(
            'todayOrders', 'todayCompleted', 'pendingOrders',
            'processingOrders', 'recentOrders', 'ordersByStatus'
        ));
    }
}