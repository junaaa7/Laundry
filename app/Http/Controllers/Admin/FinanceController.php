<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;
        $month = $request->month ?? Carbon::now()->month;
        
        // Daily revenue for selected month
        $dailyRevenue = Transaction::whereYear('order_date', $year)
            ->whereMonth('order_date', $month)
            ->where('payment_status', 'paid')
            ->select(DB::raw('DAY(order_date) as day'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('day')
            ->get();
        
        // Monthly revenue
        $monthlyRevenue = Transaction::where('payment_status', 'paid')
            ->select(DB::raw('YEAR(order_date) as year'), DB::raw('MONTH(order_date) as month'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        // Revenue by payment method
        $paymentMethodStats = Transaction::where('payment_status', 'paid')
            ->select('payment_method', DB::raw('SUM(grand_total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        // Summary
        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('grand_total');
        $totalTransactions = Transaction::count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        // Current month revenue
        $currentMonthRevenue = Transaction::whereYear('order_date', Carbon::now()->year)
            ->whereMonth('order_date', Carbon::now()->month)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        return view('admin.finance.index', compact(
            'dailyRevenue', 'monthlyRevenue', 'paymentMethodStats',
            'totalRevenue', 'totalTransactions', 'averageTransaction',
            'currentMonthRevenue', 'year', 'month'
        ));
    }
}