@extends('layouts.app')

@section('title', 'Laporan Laundry')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Laporan Laundry</h2>
            <p class="text-muted">Cetak laporan transaksi dan struk laundry</p>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('karyawan.reports') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Tampilkan
                    </button>
                    <button type="button" class="btn btn-success" onclick="printReport()">
                        <i class="fas fa-print me-2"></i>Cetak Laporan PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Transaksi</h6>
                            <h3 class="mb-0">{{ number_format($summary['total_transactions']) }}</h3>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Pendapatan</h6>
                            <h3 class="mb-0">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Berat</h6>
                            <h3 class="mb-0">{{ number_format($summary['total_weight'], 2) }} kg</h3>
                        </div>
                        <i class="fas fa-weight-hanging fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Order Selesai</h6>
                            <h3 class="mb-0">{{ number_format($summary['completed_orders']) }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Detail Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="reportsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Berat (kg)</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
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
                            <td>{{ $transaction->order_date->format('d/m/Y') }}</td>
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
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" onclick="printStruk({{ $transaction->id }})" title="Print Struk">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success" onclick="downloadStruk({{ $transaction->id }})" title="Download Struk PDF">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                    <a href="{{ route('karyawan.orders.show', $transaction) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#reportsTable').DataTable({
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [[3, 'desc']]
        });
    }
});

function printReport() {
    var startDate = $('input[name="start_date"]').val();
    var endDate = $('input[name="end_date"]').val();
    window.open('/karyawan/reports/print?start_date=' + startDate + '&end_date=' + endDate, '_blank');
}

function printStruk(transactionId) {
    window.open('/karyawan/reports/struk/' + transactionId + '?action=print', '_blank');
}

function downloadStruk(transactionId) {
    window.open('/karyawan/reports/struk/' + transactionId + '?action=download', '_blank');
}
</script>
@endpush
@endsection