@extends('layouts.app')

@section('title', 'Data Finance')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Data Finance</h2>
            <p class="text-muted">Laporan keuangan dan statistik pendapatan</p>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Pendapatan</h6>
                            <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Transaksi</h6>
                            <h3 class="mb-0">{{ number_format($totalTransactions) }}</h3>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Rata-rata Transaksi</h6>
                            <h3 class="mb-0">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-calculator fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pendapatan Bulan Ini</h6>
                            <h3 class="mb-0">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select">
                        @for($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-chart-bar me-2"></i>Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <!-- Daily Revenue Chart -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pendapatan Harian - {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Payment Method Stats -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" height="250"></canvas>
                    <div class="mt-3">
                        @foreach($paymentMethodStats as $stat)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ ucfirst($stat->payment_method ?? 'N/A') }}</span>
                            <span><strong>Rp {{ number_format($stat->total, 0, ',', '.') }}</strong> ({{ $stat->count }} transaksi)</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Rekap Pendapatan per Bulan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Total Pendapatan</th>
                            <th>Jumlah Transaksi</th>
                            <th>Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyRevenue as $revenue)
                        <tr>
                            <td>{{ $revenue->year }}</td>
                            <td>{{ date('F', mktime(0,0,0,$revenue->month,1)) }}</td>
                            <td>Rp {{ number_format($revenue->total, 0, ',', '.') }}</td>
                            <td>{{ $revenue->count ?? 0 }}</td>
                            <td>Rp {{ number_format($revenue->total / ($revenue->count ?? 1), 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Daily Revenue Chart
    const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('day')->map(function($day) { return $day; })) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($dailyRevenue->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Payment Method Chart
    const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentMethodStats->pluck('payment_method')->map(function($method) { return ucfirst($method ?? 'N/A'); })) !!},
            datasets: [{
                data: {!! json_encode($paymentMethodStats->pluck('total')) !!},
                backgroundColor: ['#ffc107', '#0dcaf0', '#198754', '#6c757d']
            }]
        }
    });
</script>
@endpush
@endsection