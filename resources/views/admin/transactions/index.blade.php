@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Data Transaksi</h2>
            <p class="text-muted">Kelola semua transaksi laundry</p>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
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
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Daftar Transaksi</h5>
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
                            <th>Berat (kg)</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Kasir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $transaction)
                        <tr>
                            <td class="text-center">{{ $index + $transactions->firstItem() }}</td>
                            <td>
                                <strong>{{ $transaction->invoice_number }}</strong>
                                <br>
                                <small class="text-muted">ID: #{{ $transaction->id }}</small>
                            </td>
                            <td>
                                {{ $transaction->customer->name }}
                                <br>
                                <small class="text-muted">{{ $transaction->customer->phone }}</small>
                            </td>
                            <td>
                                @if($transaction->order_date)
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $transaction->order_date->format('d/m/Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $transaction->order_date->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ number_format($transaction->total_weight, 2) }} kg</td>
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
                            <td>{{ $transaction->user->name }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transactions.download-invoice', $transaction) }}" class="btn btn-sm btn-secondary" title="Download Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3 d-block"></i>
                                <h5>Tidak ada data transaksi</h5>
                                <p class="text-muted">Belum ada transaksi laundry</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </div>
            </div>
            
            <!-- Pagination Manual -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $transactions->firstItem() }} sampai {{ $transactions->lastItem() }} dari {{ $transactions->total() }} hasil
                </div>
                <div class="d-flex gap-1">
                    @if ($transactions->onFirstPage())
                        <span class="btn btn-secondary disabled">
                            <i class="fas fa-chevron-left me-1"></i> Previous
                        </span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-outline-primary">
                            <i class="fas fa-chevron-left me-1"></i> Previous
                        </a>
                    @endif
                    
                    @php
                        $currentPage = $transactions->currentPage();
                        $lastPage = $transactions->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp
                    
                    @if ($start > 1)
                        <a href="{{ $transactions->url(1) }}" class="btn btn-outline-secondary">1</a>
                        @if ($start > 2)
                            <span class="btn btn-outline-secondary disabled">...</span>
                        @endif
                    @endif
                    
                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $currentPage)
                            <span class="btn btn-primary">{{ $i }}</span>
                        @else
                            <a href="{{ $transactions->url($i) }}" class="btn btn-outline-secondary">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="btn btn-outline-secondary disabled">...</span>
                        @endif
                        <a href="{{ $transactions->url($lastPage) }}" class="btn btn-outline-secondary">{{ $lastPage }}</a>
                    @endif
                    
                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-outline-primary">
                            Next <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    @else
                        <span class="btn btn-secondary disabled">
                            Next <i class="fas fa-chevron-right ms-1"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
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
    .d-flex.gap-1 {
        gap: 0.25rem;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Optional: Add any JavaScript for the page
    });
</script>
@endpush
@endsection