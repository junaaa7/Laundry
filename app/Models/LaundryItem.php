<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'service_type',
        'description',
        'price_per_kg',
        'price_multiplier',
        'price_per_piece',
        'estimated_days',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Helper untuk mendapatkan harga berdasarkan tipe layanan
    public function getPriceByServiceType($serviceType)
    {
        $multiplier = [
            'regular' => 1.00,
            'express' => 1.50,
            'vip' => 2.00
        ];
        
        $multiplierValue = $multiplier[$serviceType] ?? 1.00;
        return $this->price_per_kg * $multiplierValue;
    }

    // Helper untuk estimasi hari berdasarkan tipe layanan
    public function getEstimatedDaysByServiceType($serviceType)
    {
        $days = [
            'regular' => 3,
            'express' => 2,
            'vip' => 1
        ];
        
        return $days[$serviceType] ?? 3;
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}