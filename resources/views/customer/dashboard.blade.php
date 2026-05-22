@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Dashboard Customer</h2>
            <p class="text-muted">Selamat datang, {{ auth()->user()->name }}! {{ now()->format('d F Y H:i:s') }}</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Order Aktif</h6>
                            <h2 class="mb-0">{{ $activeOrders }}</h2>
                        </div>
                        <i class="fas fa-spinner fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Selesai</h6>
                            <h2 class="mb-0">{{ $completedOrders }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Belanja</h6>
                            <h5 class="mb-0">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h5>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Member Since</h6>
                            <h5 class="mb-0">{{ auth()->user()->created_at->format('M Y') }}</h5>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Laundry Type Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-tshirt me-2"></i>Pilih Jenis Laundry</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($laundryTypes as $type)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('customer.laundry.select', $type->category) }}" class="text-decoration-none">
                                <div class="card text-center h-100 laundry-card">
                                    <div class="card-body">
                                        @php
                                            $icons = [
                                                'pakaian' => 'fa-tshirt',
                                                'sepatu' => 'fa-shoe-prints',
                                                'tas' => 'fa-bag-shopping',
                                                'selimut' => 'fa-bed',
                                                'lainnya' => 'fa-ellipsis-h'
                                            ];
                                            $icon = $icons[$type->category] ?? 'fa-tshirt';
                                        @endphp
                                        <i class="fas {{ $icon }} fa-4x text-primary mb-3"></i>
                                        <h6 class="card-title">{{ ucfirst($type->category) }}</h6>
                                        <small class="price-text">Mulai dari Rp {{ number_format($type->price_per_kg, 0, ',', '.') }}/kg</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Transactions with Real Time -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi Terbaru</h5>
                    <a href="{{ route('customer.payments') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Tanggal Order</th>
                                    <th>Jam Order</th>
                                    <th>Berat</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <strong>{{ $transaction->invoice_number }}</strong>
                                        <br>
                                        <small class="text-muted">ID: #{{ $transaction->id }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $transaction->order_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $transaction->order_date->format('H:i:s') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $transaction->order_date->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        {{ number_format($transaction->total_weight, 2) }} kg
                                        <br>
                                        <small class="text-muted">{{ $transaction->details->count() }} item</small>
                                    </td>
                                    <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status_badge }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->payment_status_badge }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('customer.receipt.download', $transaction) }}" class="btn btn-sm btn-info" target="_blank" title="Download Struk">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @if($transaction->payment_status != 'paid')
                                            <a href="{{ route('customer.payments') }}" class="btn btn-sm btn-warning" title="Bayar">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </div>
                    
                    @if($recentTransactions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-3 d-block"></i>
                        <h5>Belum ada transaksi</h5>
                        <p class="text-muted">Silakan pilih jenis laundry di atas untuk memulai pesanan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .laundry-card {
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        border: 1px solid #dee2e6;
        border-radius: 10px;
    }
    
    .laundry-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    /* Mode Dark Styles */
    [data-bs-theme="dark"] .laundry-card {
        background-color: #2b2b2b !important;
        border-color: #444 !important;
    }
    
    [data-bs-theme="dark"] .laundry-card .card-title {
        color: #ffffff !important;
    }
    
    [data-bs-theme="dark"] .laundry-card .price-text {
        color: #aaaaaa !important;
    }
    
    .card-stats {
        transition: transform 0.3s;
        cursor: pointer;
    }
    
    .card-stats:hover {
        transform: translateY(-5px);
    }
    
    .table td {
        vertical-align: middle !important;
    }
    
    .badge {
        font-size: 0.85rem;
        padding: 5px 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto refresh setiap 60 detik untuk update status
    setTimeout(function() {
        location.reload();
    }, 60000);
</script>
@endpush
@endsection