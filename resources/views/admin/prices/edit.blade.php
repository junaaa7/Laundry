@extends('layouts.app')

@section('title', 'Edit Harga')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Harga Laundry</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.prices.update', $price) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="pakaian" {{ $price->category == 'pakaian' ? 'selected' : '' }}>Pakaian</option>
                                    <option value="sepatu" {{ $price->category == 'sepatu' ? 'selected' : '' }}>Sepatu</option>
                                    <option value="tas" {{ $price->category == 'tas' ? 'selected' : '' }}>Tas</option>
                                    <option value="selimut" {{ $price->category == 'selimut' ? 'selected' : '' }}>Selimut</option>
                                    <option value="lainnya" {{ $price->category == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Tipe Layanan <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="regular" {{ $price->type == 'regular' ? 'selected' : '' }}>Regular (2-3 hari)</option>
                                    <option value="express" {{ $price->type == 'express' ? 'selected' : '' }}>Express (1 hari)</option>
                                    <option value="vip" {{ $price->type == 'vip' ? 'selected' : '' }}>VIP (4-6 jam)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price_per_kg" class="form-label">Harga per KG <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price_per_kg') is-invalid @enderror" 
                                           id="price_per_kg" name="price_per_kg" value="{{ old('price_per_kg', $price->price_per_kg) }}" required>
                                </div>
                                @error('price_per_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="minimum_price" class="form-label">Harga Minimal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('minimum_price') is-invalid @enderror" 
                                           id="minimum_price" name="minimum_price" value="{{ old('minimum_price', $price->minimum_price) }}" required>
                                </div>
                                @error('minimum_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="delivery_days" class="form-label">Hari Pengerjaan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('delivery_days') is-invalid @enderror" 
                                           id="delivery_days" name="delivery_days" value="{{ old('delivery_days', $price->delivery_days) }}" required>
                                    <span class="input-group-text">hari</span>
                                </div>
                                @error('delivery_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="additional_fee" class="form-label">Biaya Tambahan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('additional_fee') is-invalid @enderror" 
                                           id="additional_fee" name="additional_fee" value="{{ old('additional_fee', $price->additional_fee) }}">
                                </div>
                                @error('additional_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $price->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan</label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.prices.index') }}" class="btn btn-secondary">
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