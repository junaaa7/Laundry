@extends('layouts.app')

@section('title', 'Data Order Masuk')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Data Order Masuk</h2>
            <p class="text-muted">Kelola semua order laundry yang masuk secara real time</p>
        </div>
        <div class="col text-end">
            <button class="btn btn-success" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Transaksi
            </a>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="washing" {{ request('status') == 'washing' ? 'selected' : '' }}>Washing</option>
                        <option value="drying" {{ request('status') == 'drying' ? 'selected' : '' }}>Drying</option>
                        <option value="ironing" {{ request('status') == 'ironing' ? 'selected' : '' }}>Ironing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="taken" {{ request('status') == 'taken' ? 'selected' : '' }}>Taken</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari Invoice/Customer</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Tanggal</label>
                    <input type="date" name="date_filter" class="form-control" value="{{ request('date_filter') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                    <a href="{{ route('karyawan.orders') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Daftar Order Laundry</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Tanggal Order</th>
                            <th>Jam Order</th>
                            <th>Berat (kg)</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                        <tr>
                            <td>{{ $index + $orders->firstItem() }}</td>
                            <td>
                                <strong>{{ $order->invoice_number }}</strong>
                                <br>
                                <small class="text-muted">ID: #{{ $order->id }}</small>
                            </td>
                            <td>
                                {{ $order->customer->name }}
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->customer->phone }}
                                </small>
                            </td>
                            <td>
                                @if($order->order_date)
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $order->order_date->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_date)
                                    <div>
                                        <span class="badge bg-info">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $order->order_date->format('H:i:s') }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-history me-1"></i>
                                            {{ $order->order_date->diffForHumans() }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ number_format($order->total_weight, 2) }} kg</td>
                            <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status_badge }} px-3 py-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status_badge }} px-3 py-2">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('karyawan.orders.show', $order) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($order->payment_proof)
                                    <a href="{{ route('karyawan.orders.download-proof', $order) }}" class="btn btn-sm btn-success" title="Download Bukti">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3 d-block"></i>
                                <h5>Tidak ada data order</h5>
                                <p class="text-muted">Belum ada order laundry yang masuk</p>
                                <a href="{{ route('karyawan.transactions.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i>Buat Transaksi Baru
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
        Menampilkan {{ $orders->firstItem() }} sampai {{ $orders->lastItem() }} dari {{ $orders->total() }} hasil
    </div>
    <div class="d-flex gap-1">
        <!-- Previous Page Link -->
        @if ($orders->onFirstPage())
            <span class="btn btn-secondary disabled">
                <i class="fas fa-chevron-left me-1"></i> Previous
            </span>
        @else
            <a href="{{ $orders->previousPageUrl() }}" class="btn btn-outline-primary">
                <i class="fas fa-chevron-left me-1"></i> Previous
            </a>
        @endif
        
        <!-- Page Numbers -->
        @php
            $currentPage = $orders->currentPage();
            $lastPage = $orders->lastPage();
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
        @endphp
        
        @if ($start > 1)
            <a href="{{ $orders->url(1) }}" class="btn btn-outline-secondary">1</a>
            @if ($start > 2)
                <span class="btn btn-outline-secondary disabled">...</span>
            @endif
        @endif
        
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $currentPage)
                <span class="btn btn-primary">{{ $i }}</span>
            @else
                <a href="{{ $orders->url($i) }}" class="btn btn-outline-secondary">{{ $i }}</a>
            @endif
        @endfor
        
        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <span class="btn btn-outline-secondary disabled">...</span>
            @endif
            <a href="{{ $orders->url($lastPage) }}" class="btn btn-outline-secondary">{{ $lastPage }}</a>
        @endif
        
        <!-- Next Page Link -->
        @if ($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}" class="btn btn-outline-primary">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </a>
        @else
            <span class="btn btn-secondary disabled">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </span>
        @endif
    </div>
</div>

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle !important;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
    .badge {
        font-size: 0.85rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        border-radius: 5px !important;
        margin: 0 2px;
    }
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .page-link:hover {
        background-color: #e9ecef;
    }
    .table td small {
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto refresh setiap 30 detik untuk update waktu relatif
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection