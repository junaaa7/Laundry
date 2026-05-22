<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('customer.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:notifications,id'
        ]);
        
        $notification = Notification::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->first();
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan']);
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    // TAMBAHKAN METHOD INI
    public function getTransactionDetail(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:transactions,id'
        ]);
        
        $transaction = Transaction::with(['customer', 'details.laundryItem'])
            ->where('customer_id', auth()->id())
            ->find($request->id);
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ]);
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