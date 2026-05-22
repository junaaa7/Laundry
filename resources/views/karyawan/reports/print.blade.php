<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Laundry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        .period {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary table {
            width: 300px;
            float: right;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Laundry</h1>
        <p>{{ config('app.name') }}</p>
        <p>Jl. Laundry No. 123, Kota</p>
    </div>
    
    <div class="period">
        <strong>Periode:</strong> {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Berat (kg)</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->invoice_number }}</td>
                <td>{{ $transaction->customer->name }}</td>
                <td>{{ $transaction->order_date->format('d/m/Y') }}</td>
                <td>{{ number_format($transaction->total_weight, 2) }}</td>
                <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                <td>{{ ucfirst($transaction->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="summary">
        <table>
            <tr>
                <th>Total Transaksi:</th>
                <td>{{ number_format($summary['total_transactions']) }}</td>
            </tr>
            <tr>
                <th>Total Berat:</th>
                <td>{{ number_format($summary['total_weight'], 2) }} kg</td>
            </tr>
            <tr>
                <th>Total Pendapatan:</th>
                <td>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} oleh {{ auth()->user()->name }}</p>
        <p>&copy; {{ date('Y') }} Laundry App - All rights reserved</p>
    </div>
</body>
</html>