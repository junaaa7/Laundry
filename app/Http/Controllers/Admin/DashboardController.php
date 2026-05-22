<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TargetLaundry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $today = Carbon::today();
        $todayTransactions = Transaction::whereDate('order_date', $today)->count();
        $todayRevenue = Transaction::whereDate('order_date', $today)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        // Statistik bulan ini
        $currentMonth = Carbon::now();
        $monthlyTransactions = Transaction::whereYear('order_date', $currentMonth->year)
            ->whereMonth('order_date', $currentMonth->month)
            ->count();
        $monthlyRevenue = Transaction::whereYear('order_date', $currentMonth->year)
            ->whereMonth('order_date', $currentMonth->month)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        // Target bulan ini
        $target = TargetLaundry::where('month', $currentMonth->month)
            ->where('year', $currentMonth->year)
            ->first();
        
        $targetProgress = $target ? ($monthlyRevenue / $target->target_amount) * 100 : 0;
        
        // Transaksi terbaru
        $recentTransactions = Transaction::with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Status transaksi
        $transactionStatus = Transaction::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        // Revenue chart (7 hari terakhir)
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Transaction::whereDate('order_date', $date)
                ->where('payment_status', 'paid')
                ->sum('grand_total');
            $revenueChart[] = [
                'date' => $date->format('d/m'),
                'revenue' => $revenue
            ];
        }
        
        return view('admin.dashboard', compact(
            'todayTransactions', 'todayRevenue', 'monthlyTransactions',
            'monthlyRevenue', 'targetProgress', 'recentTransactions',
            'transactionStatus', 'revenueChart'
        ));
    }
}