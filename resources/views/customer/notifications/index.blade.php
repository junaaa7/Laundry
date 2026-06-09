@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Notifikasi</h2>
            <p class="text-muted">Pemberitahuan terkait transaksi laundry Anda</p>
        </div>
        <div class="col text-end">
            <button class="btn btn-secondary" id="markAllReadBtn">
                <i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca
            </button>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            @forelse($notifications as $notification)
            <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }} mb-3 p-3 border rounded" data-id="{{ $notification->id }}" data-transaction-id="{{ $notification->transaction_id }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            @if($notification->type == 'success')
                                <i class="fas fa-check-circle text-success me-2"></i>
                            @elseif($notification->type == 'warning')
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            @elseif($notification->type == 'error')
                                <i class="fas fa-times-circle text-danger me-2"></i>
                            @else
                                <i class="fas fa-info-circle text-info me-2"></i>
                            @endif
                            
                            <h6 class="mb-0 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                {{ $notification->title }}
                            </h6>
                            
                            @if(!$notification->is_read)
                                <span class="badge bg-primary ms-2">Baru</span>
                            @endif
                        </div>
                        
                        <p class="mb-2">{{ $notification->message }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                            
                            <div>
                                @if($notification->transaction_id)
                                    <button class="btn btn-sm btn-primary view-detail" 
                                            data-id="{{ $notification->id }}"
                                            data-transaction-id="{{ $notification->transaction_id }}">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </button>
                                @endif
                                
                                @if(!$notification->is_read)
                                    <button class="btn btn-sm btn-link mark-read" data-id="{{ $notification->id }}">
                                        Tandai Dibaca
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3 d-block"></i>
                <h5>Tidak ada notifikasi</h5>
                <p class="text-muted">Belum ada pemberitahuan untuk Anda</p>
            </div>
            @endforelse
            
            <!-- Pagination yang lebih bagus -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan {{ $notifications->firstItem() }} sampai {{ $notifications->lastItem() }} dari {{ $notifications->total() }} notifikasi
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Previous Page -->
                        @if ($notifications->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                                </a>
                            </li>
                        @endif
                        
                        <!-- Page Numbers -->
                        @php
                            $currentPage = $notifications->currentPage();
                            $lastPage = $notifications->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp
                        
                        @if ($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->url(1) }}">1</a>
                            </li>
                            @if ($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif
                        
                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $currentPage)
                                <li class="page-item active">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $notifications->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor
                        
                        @if ($end < $lastPage)
                            @if ($end < $lastPage - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif
                        
                        <!-- Next Page -->
                        @if ($notifications->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->nextPageUrl() }}">
                                    Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    Selanjutnya <i class="fas fa-chevron-right ms-1"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Notifikasi -->
<div class="modal fade" id="detailModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detail Notifikasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .notification-item {
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .notification-item:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .notification-item.unread {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 3px solid #0d6efd !important;
    }
    
    /* Pagination Styles */
    .pagination {
        gap: 5px;
    }
    
    .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        padding: 8px 14px;
        color: #0d6efd;
        background-color: #fff;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        transform: translateY(-2px);
    }
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 5px rgba(13,110,253,0.3);
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.6;
    }
    
    /* Dark mode styles */
    [data-bs-theme="dark"] .page-link {
        background-color: #2d2d2d;
        border-color: #444;
        color: #e0e0e0;
    }
    
    [data-bs-theme="dark"] .page-link:hover {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    [data-bs-theme="dark"] .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    [data-bs-theme="dark"] .page-item.disabled .page-link {
        background-color: #2d2d2d;
        border-color: #444;
        color: #888;
    }
    
    [data-bs-theme="dark"] .notification-item:hover {
        background-color: rgba(255,255,255,0.05);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // View detail notification
    $('.view-detail').click(function(e) {
        e.stopPropagation();
        const transactionId = $(this).data('transaction-id');
        const notificationId = $(this).data('id');
        
        // Mark as read first
        markAsRead(notificationId, function() {
            loadTransactionDetail(transactionId);
        });
    });
    
    // Mark single notification as read
    $('.mark-read').click(function(e) {
        e.stopPropagation();
        const id = $(this).data('id');
        markAsRead(id, function() {
            location.reload();
        });
    });
    
    // Click on notification item (without button)
    $('.notification-item').click(function() {
        const transactionId = $(this).data('transaction-id');
        const notificationId = $(this).data('id');
        
        if (transactionId) {
            markAsRead(notificationId, function() {
                loadTransactionDetail(transactionId);
            });
        }
    });
    
    // Mark all read
    $('#markAllReadBtn').click(function() {
        markAllRead();
    });
});

function markAsRead(id, callback) {
    $.ajax({
        url: '{{ route("customer.notifications.mark-read") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: id
        },
        success: function(response) {
            if(response.success && callback) {
                callback();
            }
        },
        error: function(xhr) {
            console.error('Error marking as read:', xhr);
            if(callback) callback();
        }
    });
}

function markAllRead() {
    $.ajax({
        url: '{{ route("customer.notifications.mark-all-read") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            }
        },
        error: function(xhr) {
            console.error('Error marking all as read:', xhr);
        }
    });
}

function loadTransactionDetail(transactionId) {
    $('#detailContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data transaksi...</p>
        </div>
    `);
    
    $('#detailModal').modal('show');
    
    $.ajax({
        url: '/customer/transaction/detail',
        type: 'GET',
        data: { id: transactionId },
        success: function(response) {
            if(response.success) {
                displayTransactionDetail(response.data);
            } else {
                $('#detailContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Gagal memuat data transaksi: ${response.message}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            $('#detailContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Terjadi kesalahan. Silakan coba lagi.
                </div>
            `);
        }
    });
}

function displayTransactionDetail(transaction) {
    let statusHtml = '';
    const statuses = ['pending', 'processing', 'washing', 'drying', 'ironing', 'completed'];
    const statusLabels = {
        'pending': 'Pending', 'processing': 'Processing', 'washing': 'Washing',
        'drying': 'Drying', 'ironing': 'Ironing', 'completed': 'Completed'
    };
    
    statusHtml = '<div class="status-timeline">';
    let foundActive = false;
    for (let i = 0; i < statuses.length; i++) {
        let statusClass = '';
        if (transaction.status === statuses[i]) {
            statusClass = 'active';
            foundActive = true;
        } else if (foundActive) {
            statusClass = '';
        } else if (!foundActive && transaction.status !== statuses[i]) {
            statusClass = 'completed';
        }
        statusHtml += `
            <div class="status-step ${statusClass}">
                <div class="step-circle">${i + 1}</div>
                <div class="step-label">${statusLabels[statuses[i]]}</div>
            </div>
        `;
    }
    statusHtml += '</div>';
    
    let paymentClass = '';
    let paymentText = '';
    if (transaction.payment_status === 'paid') {
        paymentClass = 'paid';
        paymentText = 'LUNAS';
    } else if (transaction.payment_status === 'partial') {
        paymentClass = 'partial';
        paymentText = 'SEBAGIAN';
    } else {
        paymentClass = 'unpaid';
        paymentText = 'BELUM LUNAS';
    }
    
    const paymentHtml = `
        <div class="payment-status ${paymentClass}">
            <strong>Status Pembayaran: ${paymentText}</strong>
        </div>
    `;
    
    let itemsHtml = '';
    transaction.details.forEach((detail, idx) => {
        let serviceBadge = '';
        if (detail.service_type === 'vip') {
            serviceBadge = '<span class="badge bg-danger">VIP</span>';
        } else if (detail.service_type === 'express') {
            serviceBadge = '<span class="badge bg-warning text-dark">Express</span>';
        } else {
            serviceBadge = '<span class="badge bg-info">Regular</span>';
        }
        
        itemsHtml += `
            <tr>
                <td class="text-center">${idx + 1}</td>
                <td>${detail.laundry_item.name}</td>
                <td class="text-center">${serviceBadge}</td>
                <td class="text-center">${detail.quantity} item(s)</td>
                <td class="text-center">${detail.weight} kg<\/td>
                <td class="text-end">Rp ${formatNumber(detail.subtotal)}<\/td>
            </tr>
        `;
    });
    
    const html = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-info text-white">Informasi Transaksi<\/div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><th>Invoice</th><td><strong>${transaction.invoice_number}</strong></td></tr>
                            <tr><th>Tanggal Order</th><td>${transaction.order_date_formatted}</td></tr>
                            <tr><th>Estimasi Selesai</th><td>${transaction.completion_date_formatted || '-'}</td></tr>
                            <tr><th>Berat Total</th><td>${transaction.total_weight} kg<\/td></tr>
                            <tr><th>Grand Total</th><td>Rp ${formatNumber(transaction.grand_total)}<\/td></tr>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-success text-white">Status<\/div>
                    <div class="card-body">
                        ${statusHtml}
                        <div class="text-center mt-3">
                            <span class="badge bg-${transaction.status_badge} fs-6 px-3 py-2">
                                Status: ${transaction.status.toUpperCase()}
                            </span>
                        </div>
                        ${paymentHtml}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">Detail Item Laundry<\/div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr><th>No</th><th>Item</th><th>Tipe</th><th>Jumlah</th><th>Berat</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>${itemsHtml}</tbody>
                    </div>
                </div>
            </div>
        </div>
        ${transaction.notes ? `<div class="alert alert-warning mt-3"><i class="fas fa-sticky-note me-2"></i><strong>Catatan:</strong> ${transaction.notes}</div>` : ''}
    `;
    
    $('#detailContent').html(html);
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush
@endsection