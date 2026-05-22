<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('transactions')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('karyawan.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('karyawan.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer'
        ]);

        return redirect()->route('karyawan.customers.index')->with('success', 'Customer berhasil ditambahkan');
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }
        
        $customer->load(['transactions' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);
        
        return view('karyawan.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }
        
        return view('karyawan.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string'
        ]);

        $data = $request->except('password');
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        return redirect()->route('karyawan.customers.index')->with('success', 'Customer berhasil diupdate');
    }
}