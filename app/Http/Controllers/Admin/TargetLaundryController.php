<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TargetLaundry;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TargetLaundryController extends Controller
{
    public function index()
    {
        $targets = TargetLaundry::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);
        
        // Current month achievement
        $currentMonth = Carbon::now();
        $currentAchievement = Transaction::whereYear('order_date', $currentMonth->year)
            ->whereMonth('order_date', $currentMonth->month)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        $currentTarget = TargetLaundry::where('month', $currentMonth->month)
            ->where('year', $currentMonth->year)
            ->first();
        
        return view('admin.targets.index', compact('targets', 'currentAchievement', 'currentTarget'));
    }

    public function create()
    {
        return view('admin.targets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'target_amount' => 'required|numeric|min:0'
        ]);

        // Check if target already exists for this month/year
        $exists = TargetLaundry::where('month', $request->month)
            ->where('year', $request->year)
            ->exists();
        
        if ($exists) {
            return back()->with('error', 'Target untuk bulan dan tahun tersebut sudah ada');
        }

        TargetLaundry::create($request->all());

        return redirect()->route('admin.targets.index')->with('success', 'Target berhasil ditambahkan');
    }

    public function edit(TargetLaundry $target)
    {
        return view('admin.targets.edit', compact('target'));
    }

    public function update(Request $request, TargetLaundry $target)
    {
        $request->validate([
            'target_amount' => 'required|numeric|min:0'
        ]);

        $target->update(['target_amount' => $request->target_amount]);

        return redirect()->route('admin.targets.index')->with('success', 'Target berhasil diupdate');
    }

    public function destroy(TargetLaundry $target)
    {
        $target->delete();
        return redirect()->route('admin.targets.index')->with('success', 'Target berhasil dihapus');
    }
}