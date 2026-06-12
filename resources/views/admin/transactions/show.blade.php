@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Detail Transaksi</h2>
            <p class="text-muted">Invoice: {{ $transaction->invoice_number }}</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Customer Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Nama</th>
                            <td>{{ $transaction->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $transaction->customer->email }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $transaction->customer->phone }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $transaction->customer->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Transaction Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Tanggal Order</th>
                            <td>{{ $transaction->order_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ $transaction->completion_date ? $transaction->completion_date->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                {{-- TAMPILAN STATUS READ-ONLY (TIDAK BISA DIUBAH) --}}
                                <span class="badge bg-{{ $transaction->status_badge }} fs-6 px-3 py-2">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                <span class="badge bg-{{ $transaction->payment_status_badge }}">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $transaction->payment_method ? ucfirst($transaction->payment_method) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informasi Pick Up -->
    @if($transaction->pickup_date || $transaction->pickup_address)
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Informasi Pick Up Laundry</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fas fa-calendar-alt fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Tanggal Pick Up</label>
                        <h6 class="mb-0">
                            @if($transaction->pickup_date)
                                <strong>{{ \Carbon\Carbon::parse($transaction->pickup_date)->format('d F Y') }}</strong>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fas fa-clock fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Jam Pick Up</label>
                        <h6 class="mb-0">
                            @if($transaction->pickup_time)
                                <strong>{{ $transaction->pickup_time }} WIB</strong>
                            @else
                                <span class="text-muted">Belum diatur</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 text-center h-100">
                        <i class="fas fa-hourglass-half fa-2x text-warning mb-2 d-block"></i>
                        <label class="text-muted small">Estimasi Selesai</label>
                        <h6 class="mb-0">
                            @if($transaction->pickup_date)
                                <strong>{{ \Carbon\Carbon::parse($transaction->pickup_date)->addDays(3)->format('d F Y') }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            
            @if($transaction->pickup_address)
            <div class="mt-3 p-3 bg-light rounded">
                <label class="fw-bold"><i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Pick Up:</label>
                <p class="mb-0 mt-1">{{ $transaction->pickup_address }}</p>
            </div>
            @endif
            
            @if($transaction->pickup_notes)
            <div class="mt-3 p-3 bg-info bg-opacity-10 rounded">
                <label class="fw-bold"><i class="fas fa-sticky-note text-info me-2"></i>Catatan Pick Up:</label>
                <p class="mb-0 mt-1">{{ $transaction->pickup_notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Bukti Pembayaran -->
    @if($transaction->payment_proof)
    <div class="card mb-4 payment-proof-card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Bukti Pembayaran
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="payment-proof-info h-100">
                        <div class="proof-icon-box">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>

                        <h5 class="proof-title">Bukti Pembayaran Tersedia</h5>

                        <p class="proof-desc">
                            Customer telah mengupload bukti pembayaran untuk transaksi ini.
                            Silakan cek gambar bukti pembayaran sebelum memproses transaksi.
                        </p>

                        <div class="proof-meta">
                            <div class="proof-meta-item">
                                <span>Invoice</span>
                                <strong>{{ $transaction->invoice_number }}</strong>
                            </div>

                            <div class="proof-meta-item">
                                <span>Status Pembayaran</span>
                                <strong class="text-success">
                                    {{ ucfirst($transaction->payment_status ?? '-') }}
                                </strong>
                            </div>

                            <div class="proof-meta-item">
                                <span>Total Pembayaran</span>
                                <strong>
                                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('admin.transactions.download-proof', $transaction) }}"
                               class="btn btn-success"
                               target="_blank">
                                <i class="fas fa-download me-2"></i>Download Bukti
                            </a>

                            <a href="{{ route('admin.transactions.payment-proof', $transaction) }}"
                               class="btn btn-outline-info"
                               target="_blank">
                                <i class="fas fa-eye me-2"></i>Lihat Full
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="payment-proof-preview h-100">
                        <div class="preview-header">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-image me-2"></i>Pratinjau Bukti Pembayaran
                                </h6>
                                <small>Klik gambar untuk membuka ukuran penuh</small>
                            </div>
                        </div>

                        <a href="{{ route('admin.transactions.payment-proof', $transaction) }}"
                           target="_blank"
                           class="proof-image-link">
                            <img
                                src="{{ route('admin.transactions.payment-proof', $transaction) }}"
                                alt="Bukti Pembayaran"
                                class="proof-image"
                                onerror="this.style.display='none'; document.getElementById('proof-error-admin').style.display='block';"
                            >
                        </a>

                        <div id="proof-error-admin" class="alert alert-warning mt-3 mb-0" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Gambar bukti pembayaran tidak dapat ditampilkan. Silakan gunakan tombol download.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Items Detail -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-tshirt me-2"></i>Detail Item Laundry</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle admin-detail-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Jumlah</th>
                            <th>Berat (kg)</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $detail)
                        <tr>
                            <td>{{ $detail->laundryItem->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->weight, 2) }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total Harga:</th>
                            <th>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Diskon:</th>
                            <th>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Pajak (11%):</th>
                            <th>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</th>
                        </tr>
                        <tr class="grand-total-row">
                            <th colspan="4" class="text-end">Grand Total:</th>
                            <th><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Dibayar:</th>
                            <th>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Kembalian:</th>
                            <th>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    @if($transaction->notes)
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan</h5>
        </div>
        <div class="card-body">
            {{ $transaction->notes }}
        </div>
    </div>
    @endif
</div>

<style>
    .payment-proof-card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: var(--bs-body-bg);
    }

    .payment-proof-info {
        border-radius: 12px;
        padding: 24px;
        background: linear-gradient(135deg, rgba(13, 202, 240, 0.14), rgba(25, 135, 84, 0.10));
        border: 1px solid rgba(13, 202, 240, 0.25);
    }

    .proof-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(25, 135, 84, 0.16);
        color: #20c997;
        font-size: 26px;
        margin-bottom: 16px;
    }

    .proof-title {
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--bs-body-color);
    }

    .proof-desc {
        color: var(--bs-secondary-color);
        margin-bottom: 18px;
        line-height: 1.6;
    }

    .proof-meta {
        display: grid;
        gap: 10px;
    }

    .proof-meta-item {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 14px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .proof-meta-item span {
        color: var(--bs-secondary-color);
        font-size: 14px;
    }

    .proof-meta-item strong {
        color: var(--bs-body-color);
        text-align: right;
    }

    .payment-proof-preview {
        border-radius: 12px;
        padding: 18px;
        background: rgba(15, 23, 42, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.22);
        display: flex;
        flex-direction: column;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        color: var(--bs-body-color);
    }

    .preview-header small {
        color: var(--bs-secondary-color);
    }

    .proof-image-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 330px;
        padding: 12px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px dashed rgba(148, 163, 184, 0.35);
        text-decoration: none;
    }

    .proof-image {
        max-width: 100%;
        max-height: 430px;
        object-fit: contain;
        border-radius: 10px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .proof-image:hover {
        transform: scale(1.015);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
    }

    .admin-detail-table {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        margin-bottom: 0;
    }

    .admin-detail-table th,
    .admin-detail-table td {
        background-color: var(--bs-body-bg) !important;
        color: var(--bs-body-color) !important;
        border-color: var(--bs-border-color) !important;
        vertical-align: middle;
    }

    .admin-detail-table thead th {
        background-color: rgba(13, 110, 253, 0.10) !important;
        color: var(--bs-body-color) !important;
        font-weight: 700;
    }

    .admin-detail-table tfoot th {
        background-color: rgba(13, 110, 253, 0.06) !important;
        color: var(--bs-body-color) !important;
        font-weight: 700;
    }

    .admin-detail-table tbody tr:hover td {
        background-color: rgba(13, 202, 240, 0.08) !important;
    }

    .admin-detail-table .grand-total-row th {
        background-color: rgba(220, 53, 69, 0.12) !important;
        color: #dc3545 !important;
        font-weight: 800;
    }

    [data-bs-theme="dark"] .payment-proof-info,
    .dark .payment-proof-info,
    body.dark .payment-proof-info {
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.22), rgba(22, 163, 74, 0.13));
        border-color: rgba(34, 211, 238, 0.28);
    }

    [data-bs-theme="dark"] .payment-proof-preview,
    .dark .payment-proof-preview,
    body.dark .payment-proof-preview {
        background: #111827;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .proof-image-link,
    .dark .proof-image-link,
    body.dark .proof-image-link {
        background: #0f172a;
        border-color: #334155;
    }

    [data-bs-theme="dark"] .admin-detail-table th,
    [data-bs-theme="dark"] .admin-detail-table td,
    .dark .admin-detail-table th,
    .dark .admin-detail-table td,
    body.dark .admin-detail-table th,
    body.dark .admin-detail-table td {
        background-color: #1f2933 !important;
        color: #e5e7eb !important;
        border-color: #374151 !important;
    }

    [data-bs-theme="dark"] .admin-detail-table thead th,
    .dark .admin-detail-table thead th,
    body.dark .admin-detail-table thead th {
        background-color: #111827 !important;
        color: #ffffff !important;
    }

    [data-bs-theme="dark"] .admin-detail-table tfoot th,
    .dark .admin-detail-table tfoot th,
    body.dark .admin-detail-table tfoot th {
        background-color: #111827 !important;
        color: #ffffff !important;
    }

    [data-bs-theme="dark"] .admin-detail-table tbody tr:hover td,
    .dark .admin-detail-table tbody tr:hover td,
    body.dark .admin-detail-table tbody tr:hover td {
        background-color: #243447 !important;
    }

    [data-bs-theme="dark"] .admin-detail-table .grand-total-row th,
    .dark .admin-detail-table .grand-total-row th,
    body.dark .admin-detail-table .grand-total-row th {
        background-color: rgba(220, 53, 69, 0.16) !important;
        color: #ff6b7a !important;
    }

    @media (max-width: 768px) {
        .payment-proof-info {
            padding: 18px;
        }

        .proof-meta-item {
            flex-direction: column;
            gap: 4px;
        }

        .proof-meta-item strong {
            text-align: left;
        }

        .proof-image-link {
            min-height: 240px;
        }

        .proof-image {
            max-height: 300px;
        }
    }
</style>
@endsection