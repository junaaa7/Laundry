@extends('layouts.app')

@section('title', 'Tambah Transaksi Laundry')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Tambah Transaksi Laundry</h2>
            <p class="text-muted">Buat transaksi laundry baru untuk customer</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('karyawan.orders') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('karyawan.transactions.store') }}" id="transactionForm">
        @csrf
        
        <div class="row">
            <!-- Customer Selection -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Pilih Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="customer_id" name="customer_id" required>
                                <option value="">Pilih Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('karyawan.customers.create') }}" target="_blank" class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-2"></i>Tambah Customer Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Info -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">Cash (Tunai)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan khusus untuk order ini..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Laundry Items -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-tshirt me-2"></i>Item Laundry</h5>
            </div>
            <div class="card-body">
                <div id="items-container">
                    <div class="item-row row mb-3">
                        <div class="col-md-5">
                            <label class="form-label">Jenis Laundry</label>
                            <select class="form-select laundry-item" name="items[0][laundry_item_id]" required>
                                <option value="">Pilih Item</option>
                                @foreach($laundryItems as $item)
                                    <option value="{{ $item->id }}" data-price-regular="{{ $item->price_per_kg }}" data-price-express="{{ $item->price_per_kg * 1.5 }}">
                                        {{ $item->name }} ({{ ucfirst($item->category) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control quantity" name="items[0][quantity]" value="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Berat (kg)</label>
                            <input type="number" class="form-control weight" name="items[0][weight]" step="0.1" value="1" min="0.1" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-item" style="display: none;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <button type="button" id="add-item" class="btn btn-secondary">
                        <i class="fas fa-plus me-2"></i>Tambah Item
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Summary -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Total Harga:</th>
                                <td class="text-end"><span id="total_price_display">Rp 0</span></td>
                                <input type="hidden" id="total_price" name="total_price" value="0">
                            </tr>
                            <tr>
                                <th>Diskon:</th>
                                <td class="text-end">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" id="discount" name="discount" class="form-control form-control-sm text-end" value="0" style="width: 120px;">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Pajak (11%):</th>
                                <td class="text-end"><span id="tax_display">Rp 0</span></td>
                                <input type="hidden" id="tax" name="tax" value="0">
                            </tr>
                            <tr class="table-active">
                                <th><strong>Grand Total:</strong></th>
                                <td class="text-end"><strong><span id="grand_total_display">Rp 0</span></strong></td>
                                <input type="hidden" id="grand_total" name="grand_total" value="0">
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="{{ route('karyawan.orders') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-2"></i>Batal
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Simpan Transaksi
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
        
        // Add item row
        $('#add-item').click(function() {
            const newRow = `
                <div class="item-row row mb-3">
                    <div class="col-md-5">
                        <select class="form-select laundry-item" name="items[${itemIndex}][laundry_item_id]" required>
                            <option value="">Pilih Item</option>
                            @foreach($laundryItems as $item)
                                <option value="{{ $item->id }}" data-price-regular="{{ $item->price_per_kg }}">
                                    {{ $item->name }} ({{ ucfirst($item->category) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control weight" name="items[${itemIndex}][weight]" step="0.1" value="1" min="0.1" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#items-container').append(newRow);
            itemIndex++;
            updateTotal();
        });
        
        // Remove item row
        $(document).on('click', '.remove-item', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateTotal();
            } else {
                alert('Minimal harus ada 1 item laundry');
            }
        });
        
        // Calculate total
        $(document).on('change', '.laundry-item, .quantity, .weight, #discount', function() {
            updateTotal();
        });
        
        function updateTotal() {
            let totalPrice = 0;
            
            $('.item-row').each(function() {
                const select = $(this).find('.laundry-item option:selected');
                const price = select.data('price-regular') || 0;
                const quantity = $(this).find('.quantity').val() || 0;
                const weight = $(this).find('.weight').val() || 0;
                const itemTotal = price * quantity * weight;
                totalPrice += itemTotal;
            });
            
            const discount = parseFloat($('#discount').val()) || 0;
            const tax = totalPrice * 0.11;
            const grandTotal = totalPrice - discount + tax;
            
            $('#total_price').val(totalPrice);
            $('#total_price_display').text('Rp ' + formatNumber(totalPrice));
            $('#tax').val(tax);
            $('#tax_display').text('Rp ' + formatNumber(tax));
            $('#grand_total').val(grandTotal);
            $('#grand_total_display').text('Rp ' + formatNumber(grandTotal));
        }
        
        function formatNumber(num) {
            return num.toLocaleString('id-ID');
        }
    });
</script>
@endpush
@endsection