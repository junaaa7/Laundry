<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'order_date',
        'pickup_date',
        'pickup_time',
        'pickup_address',
        'pickup_notes',
        'completion_date',
        'taken_date',
        'total_weight',
        'total_price',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'change_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'notes'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'pickup_date' => 'date',
        'completion_date' => 'datetime',
        'taken_date' => 'datetime',
    ];

    // Set default value untuk order_date saat creating
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (empty($transaction->order_date)) {
                $transaction->order_date = Carbon::now('Asia/Jakarta');
            }
        });
    }

    // Accessor untuk format tanggal
    public function getOrderDateFormattedAttribute()
    {
        if (!$this->order_date) return '-';
        return $this->order_date->translatedFormat('d F Y');
    }
    
    public function getOrderDateTimeFormattedAttribute()
    {
        if (!$this->order_date) return '-';
        return $this->order_date->format('d/m/Y H:i:s');
    }
    
    public function getPickupDateFormattedAttribute()
    {
        if (!$this->pickup_date) return '-';
        return Carbon::parse($this->pickup_date)->translatedFormat('d F Y');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'info',
            'washing' => 'primary',
            'drying' => 'primary',
            'ironing' => 'primary',
            'completed' => 'success',
            'taken' => 'secondary',
            'cancelled' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'danger',
            'partial' => 'warning',
            'paid' => 'success'
        ];
        return $badges[$this->payment_status] ?? 'secondary';
    }
}