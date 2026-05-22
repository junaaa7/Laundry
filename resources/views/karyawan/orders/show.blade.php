@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Detail Order</h2>
            <p class="text-muted">Invoice: {{ $transaction->invoice_number }}</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('karyawan.orders') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('karyawan.orders.download-invoice', $transaction) }}" class="btn btn-secondary">
                <i class="fas fa-file-invoice me-2"></i>Download Invoice
            </a>
            @if($transaction->status != 'taken' && $transaction->status != 'cancelled')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                <i class="fas fa-sync-alt me-2"></i>Update Status
            </button>
            @endif
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print
            </button>
        </div>
    </div>
    
    <div class="row">
        <!-- Customer Info -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Nama</th>
                            <td>
                                <strong>{{ $transaction->customer->name }}</strong>
                                <br>
                                <small class="text-muted">ID Customer: #{{ $transaction->customer->id }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $transaction->customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>
                                <i class="fas fa-phone me-1"></i>
                                <a href="tel:{{ $transaction->customer->phone }}">{{ $transaction->customer->phone }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $transaction->customer->address }}</td>
                        </tr>
                        <tr>
                            <th>Member Sejak</th>
                            <td>{{ $transaction->customer->created_at->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Transaction Info -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Tanggal Order</th>
                            <td>
                                @if($transaction->order_date)
                                    <strong>{{ $transaction->order_date->format('d F Y') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $transaction->order_date->translatedFormat('l') }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Waktu Order</th>
                            <td>
                                @if($transaction->order_date)
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $transaction->order_date->format('H:i:s') }} WIB
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $transaction->order_date->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Estimasi Selesai</th>
                            <td>
                                @if($transaction->completion_date)
                                    <strong>{{ $transaction->completion_date->format('d F Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $transaction->completion_date->diffForHumans() }}</small>
                                @else
                                    <span class="text-warning">Belum ditentukan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $transaction->status_badge }} fs-6 px-3 py-2">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                <span class="badge bg-{{ $transaction->payment_status_badge }} fs-6 px-3 py-2">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>
                                @if($transaction->payment_method)
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($transaction->payment_method) }}
                                    </span>
                                @else
                                    <span class="text-muted">Belum dipilih</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kasir/Petugas</th>
                            <td>
                                <i class="fas fa-user-tie me-1"></i>
                                {{ $transaction->user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Waktu Update Terakhir</th>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-edit me-1"></i>
                                    {{ $transaction->updated_at->format('d/m/Y H:i:s') }}
                                </small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informasi Pick Up -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Informasi Pick Up Laundry</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100 pickup-card">
                        <i class="fas fa-calendar-alt fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Tanggal Pick Up</label>
                        <h6 class="mb-0 pickup-text">
                            @if($transaction->pickup_date)
                                <strong>{{ \Carbon\Carbon::parse($transaction->pickup_date)->format('d F Y') }}</strong>
                                <br>
                                <small class="text-muted pickup-small">{{ \Carbon\Carbon::parse($transaction->pickup_date)->translatedFormat('l') }}</small>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100 pickup-card">
                        <i class="fas fa-clock fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Jam Pick Up</label>
                        <h6 class="mb-0 pickup-text">
                            @if($transaction->pickup_time)
                                <strong>{{ $transaction->pickup_time }} WIB</strong>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100 pickup-card">
                        <i class="fas fa-hourglass-half fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Estimasi Selesai</label>
                        <h6 class="mb-0 pickup-text">
                            @if($transaction->completion_date)
                                <strong>{{ \Carbon\Carbon::parse($transaction->completion_date)->format('d F Y') }}</strong>
                                <br>
                                <small class="text-muted pickup-small">{{ \Carbon\Carbon::parse($transaction->completion_date)->translatedFormat('l') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 p-3 rounded pickup-address-box">
                <label class="fw-bold pickup-address-label">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Pick Up:
                </label>
                <p class="mb-0 mt-1 pickup-address-text">
                    @if($transaction->pickup_address)
                        {{ $transaction->pickup_address }}
                    @else
                        <span class="text-muted">Alamat tidak tersedia</span>
                    @endif
                </p>
            </div>
            
            @if($transaction->pickup_notes)
            <div class="mt-3 p-3 rounded pickup-notes-box">
                <label class="fw-bold pickup-notes-label">
                    <i class="fas fa-sticky-note text-info me-2"></i>Catatan Pick Up:
                </label>
                <p class="mb-0 mt-1 pickup-notes-text">{{ $transaction->pickup_notes }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Bukti Pembayaran -->
    @if(($transaction->payment_method == 'transfer' || $transaction->payment_method == 'qris') && $transaction->payment_status == 'paid')
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Bukti Pembayaran</h5>
        </div>
        <div class="card-body">
            @if($transaction->payment_proof)
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">File Bukti Pembayaran:</label>
                        <div class="mt-2">
                            <a href="{{ route('karyawan.orders.download-proof', $transaction) }}" class="btn btn-success" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Bukti Pembayaran
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Customer telah mengupload bukti pembayaran.
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <h6>Pratinjau Bukti Pembayaran:</h6>
                        <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 400px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Customer belum mengupload bukti pembayaran.
                </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Items Detail -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-tshirt me-2"></i>Detail Item Laundry</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Item Laundry</th>
                        <th>Tipe Layanan</th>
                        <th>Jumlah</th>
                        <th>Berat (kg)</th>
                        <th>Harga/kg</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->laundryItem->name }}</td>
                        <td>
                            @if($detail->service_type == 'vip')
                                <span class="badge bg-danger">VIP</span>
                            @elseif($detail->service_type == 'express')
                                <span class="badge bg-warning text-dark">Express</span>
                            @else
                                <span class="badge bg-info">Regular</span>
                            @endif
                        </td>
                        <td>{{ $detail->quantity }} item(s)</td>
                        <td>{{ number_format($detail->weight, 2) }} kg</td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end fw-bold">Total Harga</td>
                        <td class="text-end fw-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @if($transaction->discount > 0)
                    <tr>
                        <td colspan="6" class="text-end">Diskon</td>
                        <td class="text-end">Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="6" class="text-end">Pajak (11%)</td>
                        <td class="text-end">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-active">
                        <td colspan="6" class="text-end fw-bold text-danger">Grand Total</td>
                        <td class="text-end fw-bold text-danger">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    @if($transaction->payment_status != 'unpaid')
                    <tr>
                        <td colspan="6" class="text-end">Dibayar</td>
                        <td class="text-end">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end">Kembalian</td>
                        <td class="text-end">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tfoot>
            </div>
        </div>
    </div>
</div>
    
    <!-- Catatan -->
    @if($transaction->notes)
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan Customer</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $transaction->notes }}</p>
        </div>
    </div>
    @endif
    
    <!-- Timeline Status -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Timeline Status</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col">
                    <div class="step {{ $transaction->status != 'pending' ? 'completed' : ($transaction->status == 'pending' ? 'active' : '') }}">
                        <div class="circle">1</div>
                        <div>Pending</div>
                        @if($transaction->order_date)
                        <small class="text-muted">{{ $transaction->order_date->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <div class="step {{ in_array($transaction->status, ['processing','washing','drying','ironing','completed','taken']) ? 'completed' : ($transaction->status == 'processing' ? 'active' : '') }}">
                        <div class="circle">2</div>
                        <div>Processing</div>
                    </div>
                </div>
                <div class="col">
                    <div class="step {{ in_array($transaction->status, ['washing','drying','ironing','completed','taken']) ? 'completed' : ($transaction->status == 'washing' ? 'active' : '') }}">
                        <div class="circle">3</div>
                        <div>Washing</div>
                    </div>
                </div>
                <div class="col">
                    <div class="step {{ in_array($transaction->status, ['drying','ironing','completed','taken']) ? 'completed' : ($transaction->status == 'drying' ? 'active' : '') }}">
                        <div class="circle">4</div>
                        <div>Drying</div>
                    </div>
                </div>
                <div class="col">
                    <div class="step {{ in_array($transaction->status, ['ironing','completed','taken']) ? 'completed' : ($transaction->status == 'ironing' ? 'active' : '') }}">
                        <div class="circle">5</div>
                        <div>Ironing</div>
                    </div>
                </div>
                <div class="col">
                    <div class="step {{ in_array($transaction->status, ['completed','taken']) ? 'completed' : ($transaction->status == 'completed' ? 'active' : '') }}">
                        <div class="circle">6</div>
                        <div>Completed</div>
                        @if($transaction->completion_date)
                        <small class="text-muted">{{ $transaction->completion_date->format('d/m/Y') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-sync-alt me-2"></i>Update Status Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStatusForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Status Saat Ini:</strong> 
                        <span class="badge bg-{{ $transaction->status_badge }} ms-2">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Ubah Status ke</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $transaction->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="washing" {{ $transaction->status == 'washing' ? 'selected' : '' }}>Washing</option>
                            <option value="drying" {{ $transaction->status == 'drying' ? 'selected' : '' }}>Drying</option>
                            <option value="ironing" {{ $transaction->status == 'ironing' ? 'selected' : '' }}>Ironing</option>
                            <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="taken" {{ $transaction->status == 'taken' ? 'selected' : '' }}>Taken</option>
                            <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-warning" id="warningMessage" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="warningText"></span>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Customer akan menerima notifikasi setiap kali status berubah.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .step {
        position: relative;
        text-align: center;
    }
    .step .circle {
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        background: #ddd;
        color: #666;
        margin: 0 auto 10px;
        font-weight: bold;
    }
    .step.completed .circle {
        background: #28a745;
        color: white;
    }
    .step.active .circle {
        background: #007bff;
        color: white;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.3);
    }
    .step:not(:last-child):before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #ddd;
        z-index: -1;
    }
    .step.completed:before {
        background: #28a745;
    }
    
    /* Mode Dark Styles untuk Pick Up */
    [data-bs-theme="dark"] .pickup-card {
        background-color: #2d2d2d;
        border-color: #444 !important;
    }
    
    [data-bs-theme="dark"] .pickup-text {
        color: #ffffff !important;
    }
    
    [data-bs-theme="dark"] .pickup-small {
        color: #aaaaaa !important;
    }
    
    [data-bs-theme="dark"] .pickup-address-box {
        background-color: #2d2d2d;
        border: 1px solid #444;
    }
    
    [data-bs-theme="dark"] .pickup-address-label {
        color: #ff6b6b !important;
    }
    
    [data-bs-theme="dark"] .pickup-address-text {
        color: #e0e0e0 !important;
        background-color: #3a3a3a;
        padding: 10px;
        border-radius: 5px;
    }
    
    [data-bs-theme="dark"] .pickup-notes-box {
        background-color: #1e2a3a;
        border: 1px solid #2c4a6e;
    }
    
    [data-bs-theme="dark"] .pickup-notes-label {
        color: #5bc0de !important;
    }
    
    [data-bs-theme="dark"] .pickup-notes-text {
        color: #e0e0e0 !important;
        background-color: #1a2a3a;
        padding: 10px;
        border-radius: 5px;
    }
    
    /* Light Mode Styles */
    [data-bs-theme="light"] .pickup-card {
        background-color: #f8f9fa;
        border-color: #dee2e6 !important;
    }
    
    [data-bs-theme="light"] .pickup-address-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    [data-bs-theme="light"] .pickup-address-text {
        color: #333 !important;
        background-color: #e9ecef;
        padding: 10px;
        border-radius: 5px;
    }
    
    [data-bs-theme="light"] .pickup-notes-box {
        background-color: #e7f1fa;
        border: 1px solid #b8daff;
    }
    
    [data-bs-theme="light"] .pickup-notes-text {
        color: #333 !important;
        background-color: #d6e9f8;
        padding: 10px;
        border-radius: 5px;
    }
    
    .pickup-address-text, .pickup-notes-text {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    /* Table styles */
    .table td, .table th {
        vertical-align: middle !important;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
    
    tfoot td {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Show warning for taken or cancelled
    $('#status').change(function() {
        var newStatus = $(this).val();
        var warningDiv = $('#warningMessage');
        var warningText = $('#warningText');
        
        if (newStatus === 'taken') {
            warningText.html('Perhatian! Mengubah status menjadi "Taken" berarti laundry sudah diambil oleh customer. Tindakan ini tidak dapat dibatalkan.');
            warningDiv.show();
        } else if (newStatus === 'cancelled') {
            warningText.html('Perhatian! Mengubah status menjadi "Cancelled" berarti order dibatalkan. Tindakan ini tidak dapat dibatalkan.');
            warningDiv.show();
        } else {
            warningDiv.hide();
        }
    });
    
    // Submit form update status
    $('#updateStatusForm').submit(function(e) {
        e.preventDefault();
        
        var newStatus = $('#status').val();
        var confirmMessage = '';
        
        if (newStatus === 'taken') {
            confirmMessage = 'Apakah Anda yakin ingin mengubah status menjadi TAKEN? Laundry sudah diambil oleh customer.';
        } else if (newStatus === 'cancelled') {
            confirmMessage = 'Apakah Anda yakin ingin mengubah status menjadi CANCELLED? Order akan dibatalkan.';
        } else {
            confirmMessage = 'Apakah Anda yakin ingin mengubah status menjadi ' + newStatus.toUpperCase() + '?';
        }
        
        if (confirm(confirmMessage)) {
            var formData = $(this).serialize();
            var submitBtn = $('#submitBtn');
            
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');
            
            $.ajax({
                url: '{{ route("karyawan.orders.update-status", $transaction) }}',
                type: 'PUT',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        alert('Status berhasil diubah menjadi ' + newStatus.toUpperCase());
                        location.reload();
                    } else {
                        alert('Gagal mengubah status: ' + response.message);
                        submitBtn.prop('disabled', false);
                        submitBtn.html('Update Status');
                    }
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                    submitBtn.prop('disabled', false);
                    submitBtn.html('Update Status');
                }
            });
        }
    });
});
</script>
@endpush
@endsection