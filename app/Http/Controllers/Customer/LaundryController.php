<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LaundryItem;
use App\Models\Price;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    public function select($type)
    {
        $laundryItems = LaundryItem::where('category', $type)
            ->where('is_active', true)
            ->get();
        
        $prices = Price::where('is_active', true)->get();
        
        return view('customer.laundry.select', compact('laundryItems', 'prices', 'type'));
    }
}