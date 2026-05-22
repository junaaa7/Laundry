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
            <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }} mb-3 p-3 border rounded" 
                 data-id="{{ $notification->id }}" 
                 data-transaction-id="{{ $notification->transaction_id }}">
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
            
            {{ $notifications->links() }}
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
            <div class="modal-body">
                <div id="detailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>
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
    
    .status-timeline {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
    }
    
    .status-step {
        text-align: center;
        flex: 1;
        position: relative;
        z-index: 1;
    }
    
    .status-step .step-circle {
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        margin: 0 auto 8px;
        font-weight: bold;
        font-size: 14px;
    }
    
    .status-step.completed .step-circle {
        background: #28a745;
        color: white;
    }
    
    .status-step.active .step-circle {
        background: #007bff;
        color: white;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.3);
    }
    
    .status-step .step-label {
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-step.completed .step-label {
        color: #28a745;
    }
    
    .status-step.active .step-label {
        color: #007bff;
        font-weight: bold;
    }
    
    .status-timeline:before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }
    
    .payment-status {
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin-top: 15px;
    }
    
    .payment-status.paid {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .payment-status.unpaid {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .payment-status.partial {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
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
        url: '{{ route("customer.transaction.detail") }}',
        type: 'GET',
        data: {
            id: transactionId
        },
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
    // Status flow
    const statuses = ['pending', 'processing', 'washing', 'drying', 'ironing', 'completed'];
    const statusLabels = {
        'pending': 'Pending',
        'processing': 'Processing',
        'washing': 'Washing',
        'drying': 'Drying',
        'ironing': 'Ironing',
        'completed': 'Completed'
    };
    
    let statusHtml = '<div class="status-timeline">';
    let foundActive = false;
    
    for (let i = 0; i < statuses.length; i++) {
        const status = statuses[i];
        let statusClass = '';
        
        if (transaction.status === status) {
            statusClass = 'active';
            foundActive = true;
        } else if (foundActive) {
            statusClass = '';
        } else if (!foundActive && transaction.status !== status) {
            statusClass = 'completed';
        }
        
        statusHtml += `
            <div class="status-step ${statusClass}">
                <div class="step-circle">${i + 1}</div>
                <div class="step-label">${statusLabels[status]}</div>
            </div>
        `;
    }
    statusHtml += '</div>';
    
    // Payment status
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
            ${transaction.payment_status === 'paid' ? '<br><small>Pembayaran telah diverifikasi oleh admin</small>' : '<br><small>Silakan lakukan pembayaran</small>'}
        </div>
    `;
    
    // Detail items
    let itemsHtml = '';
    transaction.details.forEach((detail, index) => {
        itemsHtml += `
            <tr>
                <td>${index + 1}</td>
                <td>${detail.laundry_item.name}</td>
                <td>${detail.quantity} item(s)</td>
                <td>${detail.weight} kg</td>
                <td>Rp ${formatNumber(detail.subtotal)}</td>
            </tr>
        `;
    });
    
    const html = `
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <strong>Informasi Transaksi</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="120">Invoice</th>
                                <td><strong>${transaction.invoice_number}</strong></td>
                            </tr>
                            <tr>
                                <th>Tanggal Order</th>
                                <td>${transaction.order_date_formatted}</td>
                            </tr>
                            <tr>
                                <th>Estimasi Selesai</th>
                                <td>${transaction.completion_date_formatted || '-'}</td>
                            </tr>
                            <tr>
                                <th>Berat Total</th>
                                <td>${transaction.total_weight} kg</td>
                            </tr>
                            <tr>
                                <th>Grand Total</th>
                                <td class="text-danger fw-bold">Rp ${formatNumber(transaction.grand_total)}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <strong>Informasi Customer</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="120">Nama</th>
                                <td>${transaction.customer.name}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>${transaction.customer.email}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>${transaction.customer.phone}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <strong>Status Laundry</strong>
            </div>
            <div class="card-body">
                ${statusHtml}
                <div class="text-center mt-3">
                    <span class="badge bg-${transaction.status_badge} fs-6 px-3 py-2">
                        Status Saat Ini: ${transaction.status.toUpperCase()}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-warning text-dark">
                <strong>Status Pembayaran</strong>
            </div>
            <div class="card-body">
                ${paymentHtml}
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Detail Item Laundry</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Jumlah</th>
                                <th>Berat</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
            ${transaction.notes ? `
            <div class="card-footer">
                <strong>Catatan:</strong> ${transaction.notes}
            </div>
            ` : ''}
        </div>
    `;
    
    $('#detailContent').html(html);
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endpush
@endsection