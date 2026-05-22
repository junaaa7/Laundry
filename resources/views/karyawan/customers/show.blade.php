@extends('layouts.app')

@section('title', 'Detail Customer')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Detail Customer</h2>
            <p class="text-muted">{{ $customer->name }}</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('karyawan.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('karyawan.customers.edit', $customer) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <button class="btn btn-primary" onclick="createOrder({{ $customer->id }})">
                <i class="fas fa-shopping-cart me-2"></i>Buat Order
            </button>
        </div>
    </div>
    
    <div class="row">
        <!-- Customer Info -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="120">Nama</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $customer->address }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung</th>
                            <td>{{ $customer->created_at->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-primary mb-0">{{ $customer->transactions->count() }}</h3>
                                <small class="text-muted">Total Transaksi</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-success mb-0">
                                    Rp {{ number_format($customer->transactions->where('payment_status', 'paid')->sum('grand_total'), 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">Total Belanja</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-warning mb-0">
                                    {{ $customer->transactions->where('status', '!=', 'completed')->where('status', '!=', 'taken')->count() }}
                                </h3>
                                <small class="text-muted">Order Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Tanggal Order</th>
                            <th>Berat</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer->transactions as $transaction)
                        <tr>
                            <td><strong>{{ $transaction->invoice_number }}</strong></td>
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
                                <a href="{{ route('karyawan.orders.show', $transaction) }}" class="btn btn-sm btn-info">
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

@push('scripts')
<script>
    function createOrder(customerId) {
        window.location.href = '{{ route("karyawan.transactions.create") }}?customer_id=' + customerId;
    }
    
    $(document).ready(function() {
        $('#transactionsTable').DataTable({
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });
    });
</script>
@endpush
@endsection