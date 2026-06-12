@extends('layouts.app')

@section('title', 'Pilih Layanan Laundry - ' . ucfirst($type))

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Laundry {{ ucfirst($type) }}</h2>
            <p class="text-muted">Pilih jenis layanan laundry yang Anda inginkan (Regular, Express, atau VIP)</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('customer.order.store') }}" id="orderForm">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Pilih Item Laundry</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Tipe Layanan:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong class="text-info">Regular</strong> - Harga normal, selesai 3 hari</li>
                                <li><strong class="text-warning">Express</strong> - Harga +50%, selesai 2 hari</li>
                                <li><strong class="text-danger">VIP</strong> - Harga +100%, selesai 1 hari (prioritas)</li>
                            </ul>
                        </div>
                        
                        <div id="items-container">
                            <div class="item-row row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Jenis Laundry <span class="text-danger">*</span></label>
                                    <select class="form-select laundry-item" name="items[0][laundry_item_id]" required>
                                        <option value="">Pilih Item</option>
                                        @foreach($laundryItems as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->price_per_kg }}">
                                                {{ $item->name }} - Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}/kg
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Tipe Layanan <span class="text-danger">*</span></label>
                                    <select class="form-select service-type" name="items[0][service_type]" required>
                                        <option value="regular">🕐 Regular (3 hari) - Normal</option>
                                        <option value="express">🚀 Express (2 hari) +50%</option>
                                        <option value="vip">👑 VIP (1 hari) +100%</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control weight" name="items[0][weight]" step="0.1" value="1" min="0.1" required>
                                </div>

                                <div class="col-md-1">
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
                
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Informasi Pick Up</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="pickup_date" class="form-label">Tanggal Pick Up <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="pickup_date" name="pickup_date" 
                                   min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                            <small class="text-muted">Pilih tanggal laundry akan diambil</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pickup_time" class="form-label">Jam Pick Up <span class="text-danger">*</span></label>
                            <select class="form-select" id="pickup_time" name="pickup_time" required>
                                <option value="">Pilih Jam</option>
                                <option value="08:00-10:00">08:00 - 10:00 WIB</option>
                                <option value="10:00-12:00">10:00 - 12:00 WIB</option>
                                <option value="12:00-14:00">12:00 - 14:00 WIB</option>
                                <option value="14:00-16:00">14:00 - 16:00 WIB</option>
                                <option value="16:00-18:00">16:00 - 18:00 WIB</option>
                                <option value="18:00-20:00">18:00 - 20:00 WIB</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pickup_address" class="form-label">Alamat Pick Up <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="pickup_address" name="pickup_address" rows="2" 
                                      placeholder="Masukkan alamat lengkap untuk pengambilan laundry" required>{{ auth()->user()->address ?? '' }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pickup_notes" class="form-label">Catatan Pick Up (Opsional)</label>
                            <textarea class="form-control" id="pickup_notes" name="pickup_notes" rows="2" 
                                      placeholder="Contoh: Tolong konfirmasi sebelum pick up"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan Umum</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Contoh: tidak boleh di dryer, wangi tertentu, dll"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Total Harga:</th>
                                <td class="text-end"><span id="total_price_display">Rp 0</span></td>
                                <input type="hidden" id="total_price" name="total_price" value="0">
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
                        
                        <hr>
                        
                        <div class="alert alert-info" id="estimasiInfo">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Pilih tipe layanan untuk melihat estimasi</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn">
                            <i class="fas fa-shopping-cart me-2"></i>Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 1;

const priceMultiplier = {
    'regular': 1.00,
    'express': 1.50,
    'vip': 2.00
};

const estimatedDays = {
    'regular': 3,
    'express': 2,
    'vip': 1
};

const serviceLabels = {
    'regular': 'Regular (3 hari)',
    'express': 'Express (2 hari)',
    'vip': 'VIP (1 hari)'
};

$(document).ready(function() {
    $('#add-item').click(function() {
        const newRow = `
            <div class="item-row row mb-3 align-items-end">
                <div class="col-md-5">
                    <select class="form-select laundry-item" name="items[${itemIndex}][laundry_item_id]" required>
                        <option value="">Pilih Item</option>
                        @foreach($laundryItems as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price_per_kg }}">
                                {{ $item->name }} - Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}/kg
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <select class="form-select service-type" name="items[${itemIndex}][service_type]" required>
                        <option value="regular">🕐 Regular (3 hari) - Normal</option>
                        <option value="express">🚀 Express (2 hari) +50%</option>
                        <option value="vip">👑 VIP (1 hari) +100%</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" class="form-control weight" name="items[${itemIndex}][weight]" step="0.1" value="1" min="0.1" required>
                </div>

                <div class="col-md-1">
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
    
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            updateTotal();
        } else {
            alert('Minimal harus ada 1 item laundry');
        }
    });
    
    $(document).on('change keyup', '.laundry-item, .service-type, .weight', function() {
        updateTotal();
    });
    
    function updateTotal() {
        let totalPrice = 0;
        let fastestDays = 7;
        let hasVip = false;
        let hasExpress = false;
        
        $('.item-row').each(function() {
            const select = $(this).find('.laundry-item option:selected');
            const basePrice = select.data('price') || 0;
            const serviceType = $(this).find('.service-type').val();
            const multiplier = priceMultiplier[serviceType] || 1.00;
            const weight = parseFloat($(this).find('.weight').val()) || 0;
            
            const priceWithMultiplier = basePrice * multiplier;
            const itemTotal = priceWithMultiplier * weight;

            totalPrice += itemTotal;
            
            const days = estimatedDays[serviceType] || 3;

            if (days < fastestDays) {
                fastestDays = days;
            }
            
            if (serviceType === 'vip') hasVip = true;
            if (serviceType === 'express') hasExpress = true;
        });
        
        const tax = totalPrice * 0.11;
        const grandTotal = totalPrice + tax;
        
        $('#total_price').val(totalPrice);
        $('#total_price_display').text('Rp ' + formatNumber(totalPrice));
        $('#tax').val(tax);
        $('#tax_display').text('Rp ' + formatNumber(tax));
        $('#grand_total').val(grandTotal);
        $('#grand_total_display').text('Rp ' + formatNumber(grandTotal));
        
        let estimasiHtml = '';

        if (hasVip) {
            estimasiHtml = '<i class="fas fa-crown text-warning me-2"></i><strong>VIP Priority</strong> - Laundry akan selesai dalam <strong>1 hari</strong> (prioritas tertinggi)';
        } else if (hasExpress) {
            estimasiHtml = '<i class="fas fa-rocket text-warning me-2"></i><strong>Express Service</strong> - Laundry akan selesai dalam <strong>2 hari</strong>';
        } else {
            estimasiHtml = '<i class="fas fa-clock text-info me-2"></i><strong>Regular Service</strong> - Laundry akan selesai dalam <strong>3 hari</strong>';
        }
        
        $('#estimasiInfo').html('<i class="fas fa-info-circle me-2"></i><small>' + estimasiHtml + '</small>');
    }
    
    function formatNumber(num) {
        return num.toLocaleString('id-ID');
    }
});
</script>
@endpush
@endsection