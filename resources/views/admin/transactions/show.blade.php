@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Detail Transaksi</h2>
            <p class="text-muted">Invoice: {{ $transaction->invoice_number }}</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Customer Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Nama</th>
                            <td>{{ $transaction->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $transaction->customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $transaction->customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $transaction->customer->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Transaction Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Tanggal Order</th>
                            <td>{{ $transaction->order_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ $transaction->completion_date ? $transaction->completion_date->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                {{-- TAMPILAN STATUS READ-ONLY (TIDAK BISA DIUBAH) --}}
                                <span class="badge bg-{{ $transaction->status_badge }} fs-6 px-3 py-2">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                <span class="badge bg-{{ $transaction->payment_status_badge }}">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $transaction->payment_method ? ucfirst($transaction->payment_method) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informasi Pick Up -->
    @if($transaction->pickup_date || $transaction->pickup_address)
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Informasi Pick Up Laundry</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fas fa-calendar-alt fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Tanggal Pick Up</label>
                        <h6 class="mb-0">
                            @if($transaction->pickup_date)
                                <strong>{{ \Carbon\Carbon::parse($transaction->pickup_date)->format('d F Y') }}</strong>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fas fa-clock fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Jam Pick Up</label>
                        <h6 class="mb-0">
                            @if($transaction->pickup_time)
                                <strong>{{ $transaction->pickup_time }} WIB</strong>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fas fa-hourglass-half fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Estimasi Selesai</label>
                        <h6 class="mb-0">
                            @if($transaction->pickup_date)
                                <strong>{{ \Carbon\Carbon::parse($transaction->pickup_date)->addDays(3)->format('d F Y') }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            
            @if($transaction->pickup_address)
            <div class="mt-3 p-3 bg-light rounded">
                <label class="fw-bold"><i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Pick Up:</label>
                <p class="mb-0 mt-1">{{ $transaction->pickup_address }}</p>
            </div>
            @endif
            
            @if($transaction->pickup_notes)
            <div class="mt-3 p-3 bg-info bg-opacity-10 rounded">
                <label class="fw-bold"><i class="fas fa-sticky-note text-info me-2"></i>Catatan Pick Up:</label>
                <p class="mb-0 mt-1">{{ $transaction->pickup_notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Bukti Pembayaran -->
    @if($transaction->payment_proof)
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Bukti Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ Storage::url($transaction->payment_proof) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-download me-2"></i>Lihat Bukti Pembayaran
                    </a>
                </div>
                <div class="col-md-6">
                    <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Items Detail -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-tshirt me-2"></i>Detail Item Laundry</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Berat (kg)</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $detail)
                        <tr>
                            <td>{{ $detail->laundryItem->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->weight, 2) }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total Harga:</th>
                            <th>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Diskon:</th>
                            <th>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Pajak (11%):</th>
                            <th>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</th>
                        </tr>
                        <tr class="table-active">
                            <th colspan="4" class="text-end">Grand Total:</th>
                            <th><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Dibayar:</th>
                            <th>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Kembalian:</th>
                            <th>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    @if($transaction->notes)
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan</h5>
        </div>
        <div class="card-body">
            {{ $transaction->notes }}
        </div>
    </div>
    @endif
</div>
@endsection