<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('bank_name')->get();
        return view('admin.banks.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('qr_code');
        
        if ($request->hasFile('qr_code')) {
            $path = $request->file('qr_code')->store('qrcodes', 'public');
            $data['qr_code'] = $path;
        }

        Bank::create($data);

        return redirect()->route('admin.banks.index')->with('success', 'Bank berhasil ditambahkan');
    }

    public function edit(Bank $bank)
    {
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('qr_code');
        
        if ($request->hasFile('qr_code')) {
            if ($bank->qr_code && Storage::disk('public')->exists($bank->qr_code)) {
                Storage::disk('public')->delete($bank->qr_code);
            }
            $path = $request->file('qr_code')->store('qrcodes', 'public');
            $data['qr_code'] = $path;
        }

        $bank->update($data);

        return redirect()->route('admin.banks.index')->with('success', 'Bank berhasil diupdate');
    }

    public function destroy(Bank $bank)
    {
        if ($bank->qr_code && Storage::disk('public')->exists($bank->qr_code)) {
            Storage::disk('public')->delete($bank->qr_code);
        }
        $bank->delete();
        return redirect()->route('admin.banks.index')->with('success', 'Bank berhasil dihapus');
    }

    public function toggleStatus(Bank $bank)
    {
        try {
            $bank->update(['is_active' => !$bank->is_active]);
            return response()->json(['success' => true, 'is_active' => $bank->is_active]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}