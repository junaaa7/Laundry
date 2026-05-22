@extends('layouts.app')

@section('title', 'Data Bank')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Data Bank</h2>
            <p class="text-muted">Kelola rekening bank untuk pembayaran</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.banks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Bank
            </a>
        </div>
    </div>
    
    <div class="row">
        @foreach($banks as $bank)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-{{ $bank->is_active ? 'success' : 'secondary' }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-university me-2"></i>{{ $bank->bank_name }}</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-status" type="checkbox" 
                                   data-id="{{ $bank->id }}" {{ $bank->is_active ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="120">No. Rekening</th>
                            <td><strong>{{ $bank->account_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Atas Nama</th>
                            <td>{{ $bank->account_name }}</td>
                        </tr>
                    </table>
                    
                    @if($bank->qr_code)
                    <div class="text-center mt-3">
                        <img src="{{ Storage::url($bank->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                        <br>
                        <small class="text-muted">Scan QR Code untuk pembayaran</small>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100">
                        <a href="{{ route('admin.banks.edit', $bank) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.banks.destroy', $bank) }}" method="POST" class="d-inline w-50">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus bank ini?')">
                                <i class="fas fa-trash me-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($banks->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
        <h5>Belum ada data bank</h5>
        <p>Silakan tambahkan rekening bank untuk pembayaran customer</p>
        <a href="{{ route('admin.banks.create') }}" class="btn btn-primary">Tambah Bank</a>
    </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').change(function() {
        var id = $(this).data('id');
        var $checkbox = $(this);
        
        $.ajax({
            url: '{{ url("admin/banks") }}/' + id + '/toggle-status',
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Tampilkan notifikasi sukses
                    var status = response.is_active ? 'diaktifkan' : 'dinonaktifkan';
                    alert('Bank berhasil ' + status);
                    location.reload();
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