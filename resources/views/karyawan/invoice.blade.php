<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
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
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .total-table {
            width: 300px;
            float: right;
            margin-top: 20px;
        }
        .total-table td {
            padding: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE LAUNDRY</h1>
        <p>{{ config('app.name') }}</p>
        <p>Jl. Laundry No. 123, Kota</p>
        <p>Telp: 0812-3456-7890</p>
    </div>
    
    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Kepada Yth:</strong><br>
                {{ $transaction->customer->name }}<br>
                {{ $transaction->customer->phone }}<br>
                {{ $transaction->customer->address }}
            </td>
            <td width="50%" class="text-right">
                <strong>Invoice No:</strong> {{ $transaction->invoice_number }}<br>
                <strong>Tanggal:</strong> {{ $transaction->order_date->format('d/m/Y H:i') }}<br>
                <strong>Status:</strong> {{ ucfirst($transaction->status) }}<br>
                <strong>Pembayaran:</strong> {{ ucfirst($transaction->payment_status) }}
            </td>
        </tr>
    </table>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Item Laundry</th>
                <th>Tipe Layanan</th>
                <th>Jumlah</th>
                <th>Berat (kg)</th>
                <th>Harga/kg</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->laundryItem->name }}</td>
                <td>
                    @if($detail->service_type == 'vip')
                        VIP
                    @elseif($detail->service_type == 'express')
                        Express
                    @else
                        Regular
                    @endif
                </td>
                <td>{{ $detail->quantity }} item(s)</td>
                <td>{{ number_format($detail->weight, 2) }} kg</td>
                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <table class="total-table">
        <tr>
            <td width="70%">Total Harga:</td>
            <td class="text-right">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
        </td>
        <tr>
            <td>Diskon:</td>
            <td class="text-right">Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pajak (11%):</td>
            <td class="text-right">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 1px solid #000;">
            <td class="fw-bold">GRAND TOTAL:</td>
            <td class="text-right fw-bold">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->payment_status == 'paid')
        <tr>
            <td>Dibayar:</td>
            <td class="text-right">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembalian:</td>
            <td class="text-right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>
    
    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p>Laundry siap diambil pada: {{ $transaction->completion_date ? $transaction->completion_date->format('d/m/Y') : '-' }}</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>