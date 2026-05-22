<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        $transactions = Transaction::with('customer')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->orderBy('order_date', 'desc')
            ->get();
        
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->where('payment_status', 'paid')->sum('grand_total'),
            'total_weight' => $transactions->sum('total_weight'),
            'completed_orders' => $transactions->where('status', 'completed')->count(),
            'pending_orders' => $transactions->where('status', 'pending')->count()
        ];
        
        return view('karyawan.reports.index', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    public function printReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        
        $transactions = Transaction::with('customer')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->orderBy('order_date', 'desc')
            ->get();
        
        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->where('payment_status', 'paid')->sum('grand_total'),
            'total_weight' => $transactions->sum('total_weight')
        ];
        
        $filename = 'laporan_laundry_' . Carbon::now()->format('Ymd_His') . '.pdf';
        
        $pdf = Pdf::loadView('karyawan.reports.print', compact('transactions', 'summary', 'startDate', 'endDate'));
        
        if ($request->action === 'download') {
            return $pdf->download($filename);
        }
        
        return $pdf->stream($filename);
    }

    public function receipt(Request $request, Transaction $transaction)
    {
        $transaction->load(['customer', 'details.laundryItem']);
        
        $filename = 'struk_' . $transaction->invoice_number . '_' . Carbon::now()->format('Ymd_His') . '.pdf';
        $filename = str_replace('/', '-', $filename);
        
        $pdf = Pdf::loadView('karyawan.reports.struk', compact('transaction'));
        
        if ($request->action === 'download') {
            return $pdf->download($filename);
        }
        
        return $pdf->stream($filename);
    }
    
    public function getTransactionDetail(Request $request)
    {
        $transaction = Transaction::with(['customer', 'details.laundryItem'])
            ->find($request->id);
        
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'order_date_formatted' => $transaction->order_date ? $transaction->order_date->format('d/m/Y H:i') : '-',
                'completion_date_formatted' => $transaction->completion_date ? $transaction->completion_date->format('d/m/Y') : '-',
                'total_weight' => $transaction->total_weight,
                'total_price' => $transaction->total_price,
                'tax' => $transaction->tax,
                'grand_total' => $transaction->grand_total,
                'status' => $transaction->status,
                'status_badge' => $transaction->status_badge,
                'payment_status' => $transaction->payment_status,
                'payment_status_badge' => $transaction->payment_status_badge,
                'notes' => $transaction->notes,
                'customer' => [
                    'name' => $transaction->customer->name,
                    'email' => $transaction->customer->email,
                    'phone' => $transaction->customer->phone,
                    'address' => $transaction->customer->address
                ],
                'details' => $transaction->details->map(function($detail) {
                    return [
                        'laundry_item' => ['name' => $detail->laundryItem->name],
                        'service_type' => $detail->service_type ?? 'regular',
                        'quantity' => $detail->quantity,
                        'weight' => $detail->weight,
                        'subtotal' => $detail->subtotal
                    ];
                })
            ]
        ]);
    }
}