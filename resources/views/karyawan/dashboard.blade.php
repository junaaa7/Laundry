@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Dashboard Karyawan</h2>
            <p class="text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Order Hari Ini</h6>
                            <h2 class="mb-0">{{ $todayOrders }}</h2>
                        </div>
                        <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Selesai Hari Ini</h6>
                            <h2 class="mb-0">{{ $todayCompleted }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending Orders</h6>
                            <h2 class="mb-0">{{ $pendingOrders }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Processing</h6>
                            <h2 class="mb-0">{{ $processingOrders }}</h2>
                        </div>
                        <i class="fas fa-cogs fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Orders by Status Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribusi Status Order</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-success w-100 py-3">
                                <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                Tambah Transaksi
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('karyawan.customers.create') }}" class="btn btn-info w-100 py-3">
                                <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                                Tambah Customer
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('karyawan.orders') }}?status=pending" class="btn btn-warning w-100 py-3">
                                <i class="fas fa-clipboard-list fa-2x d-block mb-2"></i>
                                Lihat Order Pending
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('karyawan.reports') }}" class="btn btn-secondary w-100 py-3">
                                <i class="fas fa-print fa-2x d-block mb-2"></i>
                                Cetak Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Terbaru</h5>
                    <a href="{{ route('karyawan.orders') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Tanggal Order</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->invoice_number }}</strong></td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                                    <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_badge }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('karyawan.orders.show', $order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($ordersByStatus->pluck('status')->map(function($status) { return ucfirst($status); })) !!},
            datasets: [{
                data: {!! json_encode($ordersByStatus->pluck('total')) !!},
                backgroundColor: [
                    '#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#fd7e14', '#6c757d', '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection