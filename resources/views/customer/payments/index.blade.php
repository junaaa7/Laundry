@extends('layouts.app')

@section('title', 'Pembayaran Laundry')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Pembayaran Laundry</h2>
            <p class="text-muted">Lakukan pembayaran untuk transaksi laundry Anda</p>
        </div>
    </div>
    
    @if($unpaidTransactions->isEmpty())
    <div class="alert alert-success text-center">
        <i class="fas fa-check-circle fa-3x mb-3 d-block"></i>
        <h5>Tidak ada tagihan</h5>
        <p>Semua transaksi Anda sudah lunas</p>
    </div>
    @else
    <div class="row">
        <div class="col-md-7">
            @foreach($unpaidTransactions as $transaction)
            <div class="card mb-4" data-transaction-id="{{ $transaction->id }}" data-grand-total="{{ $transaction->grand_total }}">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Invoice: {{ $transaction->invoice_number }}</h5>
                        <span class="badge bg-danger">Belum Lunas</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="120">Tanggal Order</th>
                                    <td>{{ $transaction->order_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status Laundry</th>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status_badge }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Berat</th>
                                    <td>{{ number_format($transaction->total_weight, 2) }} kg</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="120">Total Harga</th>
                                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                </tr>
                                </table>
                                    <th>Pajak (11%)</th>
                                    <td>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-danger">Tagihan</th>
                                    <th class="text-danger">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <button type="button" class="btn btn-primary btn-bayar" data-id="{{ $transaction->id }}" data-total="{{ $transaction->grand_total }}">
                        <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                    </button>
                    <button type="button" class="btn btn-info btn-detail" data-id="{{ $transaction->id }}">
                        <i class="fas fa-eye me-2"></i>Lihat Detail
                    </button>
                    <a href="{{ route('customer.invoice.download', $transaction) }}" class="btn btn-secondary">
                        <i class="fas fa-file-invoice me-2"></i>Download Invoice
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="col-md-5">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Cara Pembayaran</h5>
                </div>
                <div class="card-body">
                    <h6>1. Cash (Tunai)</h6>
                    <p>Lakukan pembayaran langsung di outlet laundry kami.</p>
                    
                    <h6>2. Transfer Bank</h6>
                    <p>Transfer ke rekening bank yang tersedia. Upload bukti transfer untuk verifikasi.</p>
                    
                    <h6>3. QRIS</h6>
                    <p>Scan QR Code dan lakukan pembayaran melalui aplikasi e-wallet atau mobile banking.</p>
                    
                    <hr>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Penting:</strong> Pembayaran akan diverifikasi oleh admin. Status pembayaran akan berubah setelah verifikasi.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Detail Transaksi (Struk) -->
<div class="modal fade" id="detailModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Detail Transaksi</h5>
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
                <button type="button" class="btn btn-info" onclick="printStruk()">
                    <i class="fas fa-print me-2"></i>Print Struk
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i>Form Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label">Total Tagihan</label>
                        <h4 class="text-danger" id="totalTagihan">Rp 0</h4>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Pilih Metode</option>
                            <option value="cash">Cash (Tunai di Outlet)</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    
                    <!-- Upload Bukti Pembayaran (untuk Transfer dan QRIS) -->
                    <div class="mb-3" id="paymentProofDiv" style="display: none;">
                        <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                        <small class="text-muted">Upload screenshot/foto bukti transfer (max 2MB)</small>
                    </div>
                    
                    <!-- Info Bank (hanya untuk Transfer) -->
                    <div id="bankInfoDiv" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-university me-2"></i>
                            <strong>Rekening Tujuan Transfer:</strong>
                        </div>
                        <div id="bankList" class="mb-3">
                            <!-- Data bank akan diisi oleh JavaScript -->
                        </div>
                    </div>
                    
                    <!-- QR Code (hanya untuk QRIS) -->
                    <div id="qrisInfoDiv" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-qrcode me-2"></i>
                            <strong>Scan QR Code Berikut:</strong>
                        </div>
                        <div id="qrisList" class="mb-3 text-center">
                            <!-- Data QR Code akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
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
    .pickup-card {
        transition: all 0.3s;
    }
    .pickup-address-text, .pickup-notes-text {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .table td, .table th {
        vertical-align: middle !important;
    }
    .badge {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
    
    /* Print Styles - Hanya untuk mencetak struk */
    @media print {
        /* Sembunyikan semua elemen di halaman */
        body * {
            visibility: hidden;
        }
        
        /* Tampilkan hanya modal content */
        .modal-content, .modal-content * {
            visibility: visible;
        }
        
        /* Atur posisi modal untuk print */
        .modal-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
        }
        
        /* Sembunyikan header dan footer modal */
        .modal-header, .modal-footer {
            display: none !important;
        }
        
        /* Sembunyikan semua tombol */
        .btn, .btn-close, button {
            display: none !important;
        }
        
        /* Atur margin body untuk print */
        .modal-body {
            padding: 20px;
            margin: 0;
        }
        
        /* Pastikan card tidak terpotong */
        .card {
            border: 1px solid #ddd !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        
        /* Warna badge menjadi hitam putih untuk print */
        .badge {
            border: 1px solid #000 !important;
            background-color: #fff !important;
            color: #000 !important;
        }
        
        /* Warna teks untuk print */
        .text-danger {
            color: #000 !important;
        }
        
        .text-primary {
            color: #000 !important;
        }
        
        .text-success {
            color: #000 !important;
        }
        
        /* Tabel border untuk print */
        .table-bordered td, .table-bordered th {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Data bank dari server
const banks = @json(\App\Models\Bank::where('is_active', true)->get());

// Variabel untuk menyimpan transactionId
let currentTransactionId = null;

// Event listener untuk tombol bayar
document.querySelectorAll('.btn-bayar').forEach(button => {
    button.addEventListener('click', function() {
        const transactionId = this.getAttribute('data-id');
        const grandTotal = parseInt(this.getAttribute('data-total'));
        
        currentTransactionId = transactionId;
        
        // Update modal
        document.getElementById('totalTagihan').innerText = 'Rp ' + formatNumber(grandTotal);
        document.getElementById('paymentForm').action = '/customer/payments/' + transactionId;
        
        // Reset form
        document.getElementById('payment_method').value = '';
        document.getElementById('paymentProofDiv').style.display = 'none';
        document.getElementById('bankInfoDiv').style.display = 'none';
        document.getElementById('qrisInfoDiv').style.display = 'none';
        document.getElementById('payment_proof').value = '';
        
        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    });
});

// Event listener untuk tombol lihat detail
document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', function() {
        const transactionId = this.getAttribute('data-id');
        showTransactionDetail(transactionId);
    });
});

// Fungsi untuk menampilkan detail transaksi
function showTransactionDetail(transactionId) {
    document.getElementById('detailContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data transaksi...</p>
        </div>
    `;
    
    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    detailModal.show();
    
    fetch('/customer/transaction/detail?id=' + transactionId, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayTransactionDetail(data.data);
        } else {
            document.getElementById('detailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Gagal memuat data: ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        document.getElementById('detailContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Terjadi kesalahan. Silakan coba lagi.
            </div>
        `;
    });
}

// Fungsi untuk menampilkan detail transaksi di modal
function displayTransactionDetail(transaction) {
    let itemsHtml = '';
    transaction.details.forEach((detail, idx) => {
        let serviceTypeBadge = '';
        if (detail.service_type === 'vip') {
            serviceTypeBadge = '<span class="badge bg-danger">VIP</span>';
        } else if (detail.service_type === 'express') {
            serviceTypeBadge = '<span class="badge bg-warning text-dark">Express</span>';
        } else {
            serviceTypeBadge = '<span class="badge bg-info">Regular</span>';
        }
        
        itemsHtml += `
            <tr>
                <td class="text-center">${idx + 1}</td>
                <td>${detail.laundry_item.name}</td>
                <td class="text-center">${serviceTypeBadge}</td>
                <td class="text-center">${detail.quantity} item(s)</td>
                <td class="text-center">${detail.weight} kg</td>
                <td class="text-end">Rp ${formatNumber(detail.subtotal)}</td>
            </tr>
        `;
    });
    
    const paymentStatusClass = transaction.payment_status === 'paid' ? 'success' : (transaction.payment_status === 'partial' ? 'warning' : 'danger');
    const paymentStatusText = transaction.payment_status === 'paid' ? 'LUNAS' : (transaction.payment_status === 'partial' ? 'SEBAGIAN' : 'BELUM LUNAS');
    
    const html = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-user me-2"></i> Informasi Customer
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="35%"><strong>Nama Lengkap</strong></td>
                                <td width="65%">: ${transaction.customer.name}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: ${transaction.customer.email}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Telepon</strong></td>
                                <td>: ${transaction.customer.phone}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>: ${transaction.customer.address || '-'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-info-circle me-2"></i> Informasi Transaksi
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td width="40%"><strong>Nomor Invoice</strong></td>
                                <td width="60%">: <strong class="text-primary">${transaction.invoice_number}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Order</strong></td>
                                <td>: ${transaction.order_date_formatted}</td>
                            </tr>
                            <tr>
                                <td><strong>Estimasi Selesai</strong></td>
                                <td>: ${transaction.completion_date_formatted || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Berat</strong></td>
                                <td>: ${transaction.total_weight} kg</td>
                            </tr>
                            <tr>
                                <td><strong>Status Laundry</strong></td>
                                <td>: <span class="badge bg-${transaction.status_badge}">${transaction.status}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Status Pembayaran</strong></td>
                                <td>: <span class="badge bg-${paymentStatusClass}">${paymentStatusText}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-tshirt me-2"></i> Detail Item Laundry
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="35%">Item Laundry</th>
                                <th class="text-center" width="15%">Tipe Layanan</th>
                                <th class="text-center" width="10%">Jumlah</th>
                                <th class="text-center" width="15%">Berat (kg)</th>
                                <th class="text-end" width="20%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">Total Harga:</th>
                                <td class="text-end fw-bold">Rp ${formatNumber(transaction.total_price)}</td>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-end">Pajak (11%):</th>
                                <td class="text-end">Rp ${formatNumber(transaction.tax)}</td>
                            </tr>
                            <tr class="table-active">
                                <th colspan="5" class="text-end fw-bold text-danger">Grand Total:</th>
                                <td class="text-end fw-bold text-danger">Rp ${formatNumber(transaction.grand_total)}</td>
                            </tr>
                        </tfoot>
                    </div>
                </div>
            </div>
        </div>
        ${transaction.notes ? `<div class="alert alert-warning"><i class="fas fa-sticky-note me-2"></i><strong>Catatan:</strong> ${transaction.notes}</div>` : ''}
    `;
    
    document.getElementById('detailContent').innerHTML = html;
}

// Fungsi untuk print struk (hanya modal, bukan seluruh halaman)
function printStruk() {
    // Simpan judul halaman asli
    var originalTitle = document.title;
    document.title = 'Struk Laundry - ' + new Date().toLocaleString();
    
    // Cetak
    window.print();
    
    // Kembalikan judul halaman
    document.title = originalTitle;
}

// Event listener untuk perubahan metode pembayaran
document.getElementById('payment_method').addEventListener('change', function() {
    const paymentMethod = this.value;
    const paymentProofDiv = document.getElementById('paymentProofDiv');
    const bankInfoDiv = document.getElementById('bankInfoDiv');
    const qrisInfoDiv = document.getElementById('qrisInfoDiv');
    const bankList = document.getElementById('bankList');
    const qrisList = document.getElementById('qrisList');
    
    // Sembunyikan semua terlebih dahulu
    paymentProofDiv.style.display = 'none';
    bankInfoDiv.style.display = 'none';
    qrisInfoDiv.style.display = 'none';
    
    if (paymentMethod === 'transfer') {
        paymentProofDiv.style.display = 'block';
        
        if (banks.length > 0) {
            let bankHtml = '';
            banks.forEach(bank => {
                bankHtml += `
                    <div class="card mb-2">
                        <div class="card-body">
                            <strong class="text-primary">${bank.bank_name}</strong><br>
                            <strong>No. Rekening:</strong> ${bank.account_number}<br>
                            <strong>Atas Nama:</strong> ${bank.account_name}
                        </div>
                    </div>
                `;
            });
            bankList.innerHTML = bankHtml;
            bankInfoDiv.style.display = 'block';
        } else {
            bankList.innerHTML = '<div class="alert alert-warning">Belum ada data bank. Silakan hubungi admin.</div>';
            bankInfoDiv.style.display = 'block';
        }
    } 
    else if (paymentMethod === 'qris') {
        paymentProofDiv.style.display = 'block';
        
        if (banks.length > 0) {
            let qrisHtml = '';
            banks.forEach(bank => {
                if (bank.qr_code) {
                    qrisHtml += `
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <strong class="text-primary">${bank.bank_name}</strong>
                                <div class="mt-2">
                                    <img src="/storage/${bank.qr_code}" alt="QR Code" class="img-fluid" style="max-width: 200px; border: 1px solid #ddd; border-radius: 10px; padding: 10px;">
                                </div>
                                <p class="small text-muted mt-2">Scan QR Code di atas untuk melakukan pembayaran</p>
                            </div>
                        </div>
                    `;
                } else {
                    qrisHtml += `
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <strong class="text-primary">${bank.bank_name}</strong>
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    QR Code belum tersedia. Silakan hubungi admin.
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            qrisList.innerHTML = qrisHtml;
            qrisInfoDiv.style.display = 'block';
        } else {
            qrisList.innerHTML = '<div class="alert alert-warning">Belum ada data pembayaran QRIS. Silakan hubungi admin.</div>';
            qrisInfoDiv.style.display = 'block';
        }
    }
});

// Fungsi format number
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Submit form dengan AJAX
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pembayaran berhasil! Menunggu verifikasi admin.');
            location.reload();
        } else {
            alert('Gagal: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Konfirmasi Pembayaran';
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan. Silakan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Konfirmasi Pembayaran';
    });
});
</script>
@endpush
@endsection