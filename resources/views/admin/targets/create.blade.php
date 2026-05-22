@extends('layouts.app')

@section('title', 'Tambah Target')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Target Bulanan</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.targets.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="month" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <select class="form-select @error('month') is-invalid @enderror" id="month" name="month" required>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('month') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0,0,0,$i,1)) }}
                                        </option>
                                    @endfor
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <select class="form-select @error('year') is-invalid @enderror" id="year" name="year" required>
                                    @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                        <option value="{{ $i }}" {{ old('year', date('Y')) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">Target Pendapatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('target_amount') is-invalid @enderror" 
                                       id="target_amount" name="target_amount" value="{{ old('target_amount') }}" 
                                       placeholder="0" required>
                            </div>
                            @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan target pendapatan untuk bulan tersebut</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Pencapaian target akan dihitung otomatis dari total transaksi yang sudah lunas.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection