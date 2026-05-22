<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->date_filter) {
            $query->whereDate('order_date', $request->date_filter);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'details.laundryItem']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,washing,drying,ironing,completed,taken,cancelled'
        ]);
        
        $oldStatus = $transaction->status;
        $newStatus = $request->status;
        
        if ($newStatus === 'cancelled' && $transaction->payment_status === 'paid') {
            return response()->json([
                'success' => false, 
                'message' => 'Tidak dapat membatalkan order yang sudah lunas!'
            ]);
        }
        
        if ($newStatus === 'taken' && $transaction->status !== 'completed') {
            return response()->json([
                'success' => false, 
                'message' => 'Order harus berstatus "Completed" terlebih dahulu sebelum diambil!'
            ]);
        }
        
        $updateData = ['status' => $newStatus];
        
        if ($newStatus === 'completed' && !$transaction->completion_date) {
            $updateData['completion_date'] = Carbon::now();
        }
        
        if ($newStatus === 'taken' && !$transaction->taken_date) {
            $updateData['taken_date'] = Carbon::now();
        }
        
        $transaction->update($updateData);
        
        $statusMessages = [
            'pending' => 'menunggu konfirmasi',
            'processing' => 'sedang diproses',
            'washing' => 'sedang dicuci',
            'drying' => 'sedang dikeringkan',
            'ironing' => 'sedang disetrika',
            'completed' => 'selesai dan siap diambil',
            'taken' => 'sudah diambil oleh customer',
            'cancelled' => 'dibatalkan'
        ];
        
        $message = "Status laundry Anda untuk invoice {$transaction->invoice_number} telah berubah dari " . 
                   ucfirst($oldStatus) . " menjadi " . ucfirst($newStatus) . 
                   " (" . ($statusMessages[$newStatus] ?? $newStatus) . ")";
        
        Notification::create([
            'user_id' => $transaction->customer_id,
            'transaction_id' => $transaction->id,
            'title' => 'Update Status Laundry',
            'message' => $message,
            'type' => $newStatus === 'cancelled' ? 'warning' : 'info',
            'channel' => 'web',
            'is_sent' => true,
            'sent_at' => Carbon::now()
        ]);
        
        return response()->json(['success' => true, 'message' => 'Status berhasil diupdate']);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }
    
    public function downloadInvoice(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'details.laundryItem']);
        
        $filename = 'invoice_' . $transaction->invoice_number . '_' . Carbon::now()->format('Ymd_His') . '.pdf';
        $filename = str_replace('/', '-', $filename);
        
        $pdf = Pdf::loadView('admin.invoice', compact('transaction'));
        
        return $pdf->download($filename);
    }
}