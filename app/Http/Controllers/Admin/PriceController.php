<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::orderBy('category')->orderBy('type')->get();
        return view('admin.prices.index', compact('prices'));
    }

    public function create()
    {
        return view('admin.prices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'type' => 'required|string',
            'price_per_kg' => 'required|numeric|min:0',
            'minimum_price' => 'required|numeric|min:0',
            'delivery_days' => 'required|integer|min:1',
            'additional_fee' => 'nullable|numeric|min:0'
        ]);

        Price::create($request->all());

        return redirect()->route('admin.prices.index')->with('success', 'Harga berhasil ditambahkan');
    }

    public function edit(Price $price)
    {
        return view('admin.prices.edit', compact('price'));
    }

    public function update(Request $request, Price $price)
    {
        $request->validate([
            'category' => 'required|string',
            'type' => 'required|string',
            'price_per_kg' => 'required|numeric|min:0',
            'minimum_price' => 'required|numeric|min:0',
            'delivery_days' => 'required|integer|min:1',
            'additional_fee' => 'nullable|numeric|min:0'
        ]);

        $price->update($request->all());

        return redirect()->route('admin.prices.index')->with('success', 'Harga berhasil diupdate');
    }

    public function destroy(Price $price)
    {
        $price->delete();
        return redirect()->route('admin.prices.index')->with('success', 'Harga berhasil dihapus');
    }

    public function toggleStatus(Price $price)
    {
        try {
            $price->update(['is_active' => !$price->is_active]);
            return response()->json(['success' => true, 'is_active' => $price->is_active]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}