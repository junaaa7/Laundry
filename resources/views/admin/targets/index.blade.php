@extends('layouts.app')

@section('title', 'Atur Target Laundry')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Atur Target Laundry</h2>
            <p class="text-muted">Kelola target pendapatan per bulan</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('admin.targets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Target
            </a>
        </div>
    </div>
    
    <!-- Current Month Progress -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Progress Target Bulan Ini</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="progress" style="height: 30px;">
                        @php
                            $progress = $currentTarget ? ($currentAchievement / $currentTarget->target_amount) * 100 : 0;
                        @endphp
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" 
                             style="width: {{ min($progress, 100) }}%" 
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($progress, 1) }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <h4 class="mb-0">
                        Rp {{ number_format($currentAchievement, 0, ',', '.') }} 
                        <small class="text-muted">/ Rp {{ number_format($currentTarget ? $currentTarget->target_amount : 0, 0, ',', '.') }}</small>
                    </h4>
                    <small class="text-muted">Target {{ date('F Y') }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Targets Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Target per Bulan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="targetsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Target Pendapatan</th>
                            <th>Pencapaian</th>
                            <th>Progress</th>
                            <th>Sisa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($targets as $index => $target)
                        @php
                            $progress = $target->progress;
                            $remaining = $target->remaining;
                            $achieved = $target->achieved_amount;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ date('F', mktime(0,0,0,$target->month,1)) }}</td>
                            <td>{{ $target->year }}</td>
                            <td>Rp {{ number_format($target->target_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($achieved, 0, ',', '.') }}</td>
                            <td>
                                <div class="progress" style="width: 100px;">
                                    <div class="progress-bar bg-{{ $progress >= 100 ? 'success' : ($progress >= 70 ? 'warning' : 'info') }}" 
                                         role="progressbar" 
                                         style="width: {{ min($progress, 100) }}%">
                                        {{ number_format($progress, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($remaining > 0)
                                    <span class="text-danger">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Tercapai</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.targets.edit', $target) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.targets.destroy', $target) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus target ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $targets->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#targetsTable').DataTable({
            pageLength: 12,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            searching: false,
            paging: false,
            info: false
        });
    });
</script>
@endpush
@endsection