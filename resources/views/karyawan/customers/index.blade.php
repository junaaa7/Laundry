@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Data Customer</h2>
            <p class="text-muted">Kelola semua customer laundry</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('karyawan.customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Customer
            </a>
        </div>
    </div>
    
    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Cari customer (nama, email, telepon)..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Customers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="customersTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Total Transaksi</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $index => $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $customer->name }}</strong><br>
                                <small class="text-muted">ID: #{{ $customer->id }}</small>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ Str::limit($customer->address, 30) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $customer->transactions_count }} transaksi
                                </span>
                            </td>
                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('karyawan.customers.show', $customer) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('karyawan.customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="createOrder({{ $customer->id }})">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function createOrder(customerId) {
        window.location.href = '{{ route("karyawan.transactions.create") }}?customer_id=' + customerId;
    }
    
    $(document).ready(function() {
        $('#customersTable').DataTable({
            pageLength: 15,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            searching: false,
            paging: false,
            info: false
        });
    });
</script>
@endpush
@endsection