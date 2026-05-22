@extends('layouts.app')

@section('title', 'Data Harga')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Data Harga Laundry</h2>
            <p class="text-muted">Kelola harga untuk setiap jenis layanan laundry (Regular, Express, VIP)</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.prices.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Harga
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="pricesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Tipe Layanan</th>
                            <th>Harga/kg</th>
                            <th>Harga Minimal</th>
                            <th>Hari Pengerjaan</th>
                            <th>Biaya Tambahan</th>
                            <th>Multiplier</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prices as $index => $price)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($price->category) }}</td>
                            <td>
                                @if($price->type == 'vip')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-crown me-1"></i>VIP
                                    </span>
                                @elseif($price->type == 'express')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-rocket me-1"></i>Express
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>Regular
                                    </span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($price->price_per_kg, 0, ',', '.') }} /kg</td>
                            <td>Rp {{ number_format($price->minimum_price, 0, ',', '.') }}</td>
                            <td>
                                @if($price->type == 'vip')
                                    <span class="text-danger">{{ $price->delivery_days }} hari (Prioritas)</span>
                                @elseif($price->type == 'express')
                                    <span class="text-warning">{{ $price->delivery_days }} hari (Cepat)</span>
                                @else
                                    <span>{{ $price->delivery_days }} hari (Normal)</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($price->additional_fee ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if($price->type == 'vip')
                                    <span class="text-danger">2x Lipat</span>
                                @elseif($price->type == 'express')
                                    <span class="text-warning">1.5x Lipat</span>
                                @else
                                    <span>1x Lipat</span>
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox" 
                                           data-id="{{ $price->id }}" {{ $price->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.prices.edit', $price) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.prices.destroy', $price) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus harga ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge i {
        margin-right: 4px;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#pricesTable').DataTable({
        pageLength: 10,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        order: [[2, 'asc']]
    });
    
    $('.toggle-status').change(function() {
        var id = $(this).data('id');
        var $checkbox = $(this);
        
        $.ajax({
            url: '{{ url("admin/prices") }}/' + id + '/toggle-status',
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    var status = response.is_active ? 'diaktifkan' : 'dinonaktifkan';
                    toastr.success('Harga berhasil ' + status);
                } else {
                    alert('Gagal mengubah status: ' + response.message);
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                $checkbox.prop('checked', !$checkbox.prop('checked'));
            }
        });
    });
});
</script>
@endpush
@endsection