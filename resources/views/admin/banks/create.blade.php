@extends('layouts.app')

@section('title', 'Tambah Bank')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Rekening Bank</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.banks.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
                            <select class="form-select @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" required>
                                <option value="">Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="Mandiri">Bank Mandiri</option>
                                <option value="BNI">BNI</option>
                                <option value="BRI">BRI</option>
                                <option value="CIMB Niaga">CIMB Niaga</option>
                                <option value="Danamon">Danamon</option>
                                <option value="Permata">Permata Bank</option>
                                <option value="OCBC NISP">OCBC NISP</option>
                                <option value="Maybank">Maybank</option>
                                <option value="BSI">BSI</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                   id="account_number" name="account_number" value="{{ old('account_number') }}" required>
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="account_name" class="form-label">Atas Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                   id="account_name" name="account_name" value="{{ old('account_name') }}" required>
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="qr_code" class="form-label">QR Code (Opsional)</label>
                            <input type="file" class="form-control @error('qr_code') is-invalid @enderror" 
                                   id="qr_code" name="qr_code" accept="image/*">
                            <small class="text-muted">Upload gambar QRIS atau QR Code pembayaran (max 2MB)</small>
                            @error('qr_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Aktifkan (ditampilkan ke customer)</label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection