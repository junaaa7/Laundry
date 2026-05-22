<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'laundry_item_id',
        'service_type',
        'quantity',
        'weight',
        'price',
        'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function laundryItem()
    {
        return $this->belongsTo(LaundryItem::class);
    }
}