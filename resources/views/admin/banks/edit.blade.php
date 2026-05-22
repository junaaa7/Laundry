@extends('layouts.app')

@section('title', 'Edit Bank')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Rekening Bank: {{ $bank->bank_name }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.banks.update', $bank) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
                            <select class="form-select @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" required>
                                <option value="BCA" {{ $bank->bank_name == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="Mandiri" {{ $bank->bank_name == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                <option value="BNI" {{ $bank->bank_name == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="BRI" {{ $bank->bank_name == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="CIMB Niaga" {{ $bank->bank_name == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                <option value="Danamon" {{ $bank->bank_name == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                <option value="Permata" {{ $bank->bank_name == 'Permata' ? 'selected' : '' }}>Permata Bank</option>
                                <option value="OCBC NISP" {{ $bank->bank_name == 'OCBC NISP' ? 'selected' : '' }}>OCBC NISP</option>
                                <option value="Maybank" {{ $bank->bank_name == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                <option value="BSI" {{ $bank->bank_name == 'BSI' ? 'selected' : '' }}>BSI</option>
                                <option value="Lainnya" {{ $bank->bank_name == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                   id="account_number" name="account_number" value="{{ old('account_number', $bank->account_number) }}" required>
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="account_name" class="form-label">Atas Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                   id="account_name" name="account_name" value="{{ old('account_name', $bank->account_name) }}" required>
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($bank->qr_code)
                        <div class="mb-3">
                            <label class="form-label">QR Code Saat Ini</label>
                            <div class="text-center">
                                <img src="{{ Storage::url($bank->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                            </div>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="qr_code" class="form-label">Ganti QR Code (Opsional)</label>
                            <input type="file" class="form-control @error('qr_code') is-invalid @enderror" 
                                   id="qr_code" name="qr_code" accept="image/*">
                            <small class="text-muted">Upload gambar QRIS baru (max 2MB). Kosongkan jika tidak ingin mengubah.</small>
                            @error('qr_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $bank->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan (ditampilkan ke customer)</label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection