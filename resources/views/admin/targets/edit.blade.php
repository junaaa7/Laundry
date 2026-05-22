@extends('layouts.app')

@section('title', 'Edit Target')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Target: {{ date('F Y', mktime(0,0,0,$target->month,1,$target->year)) }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.targets.update', $target) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">Target Pendapatan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('target_amount') is-invalid @enderror" 
                                       id="target_amount" name="target_amount" value="{{ old('target_amount', $target->target_amount) }}" 
                                       required>
                            </div>
                            @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-chart-line me-2"></i>
                            Pencapaian saat ini: <strong>Rp {{ number_format($target->achieved_amount, 0, ',', '.') }}</strong>
                            ({{ number_format($target->progress, 1) }}%)
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Target
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection