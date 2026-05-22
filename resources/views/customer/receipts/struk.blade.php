<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Laundry - {{ $transaction->invoice_number }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        
        .header p {
            margin: 3px 0;
        }
        
        .info {
            margin-bottom: 15px;
        }
        
        .info table {
            width: 100%;
        }
        
        .items {
            margin-bottom: 15px;
        }
        
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items th, .items td {
            border-bottom: 1px dotted #ccc;
            padding: 5px 0;
            text-align: left;
        }
        
        .items th {
            font-weight: bold;
        }
        
        .total {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        
        .total table {
            width: 100%;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 8px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        
        .barcode {
            text-align: center;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Struk
    </button>
    
    <div class="header">
        <h2>{{ config('app.name') }}</h2>
        <p>Laundry Express</p>
        <p>Jl. Laundry No. 123, Kota</p>
        <p>Telp: 0812-3456-7890</p>
        <p>Email: info@laundryapp.com</p>
    </div>
    
    <div class="info">
        <table>
            <tr>
                <td width="40%">Invoice:</td>
                <td><strong>{{ $transaction->invoice_number }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Order:</td>
                <td>{{ $transaction->order_date->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Customer:</td>
                <td>{{ $transaction->customer->name }}</td>
            </tr>
            <tr>
                <td>Telepon:</td>
                <td>{{ $transaction->customer->phone }}</td>
            </tr>
        </table>
    </div>
    
    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Berat</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $detail)
                <tr>
                    <td>{{ $detail->laundryItem->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->weight, 1) }} kg</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="total">
        <table>
            <tr>
                <td width="60%">Total Harga:</td>
                <td class="text-end">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->discount > 0)
            <tr>
                <td>Diskon:</td>
                <td class="text-end">Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td>Pajak (11%):</td>
                <td class="text-end">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 1px solid #000;">
                <td><strong>Grand Total:</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></td>
            </tr>
            @if($transaction->payment_status == 'paid')
            <tr>
                <td>Dibayar:</td>
                <td class="text-end">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian:</td>
                <td class="text-end">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="barcode">
        <small>{{ $transaction->invoice_number }}</small>
    </div>
    
    <div class="footer">
        <p><strong>Status Laundry: {{ ucfirst($transaction->status) }}</strong></p>
        <p><strong>Status Pembayaran: {{ ucfirst($transaction->payment_status) }}</strong></p>
        @if($transaction->completion_date)
        <p>Laundry siap diambil pada: {{ $transaction->completion_date->format('d/m/Y') }}</p>
        @endif
        <p>Terima kasih atas kepercayaan Anda!</p>
        <p>Simpan struk ini sebagai bukti transaksi</p>
        <hr>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment below to auto print
            // window.print();
        }
    </script>
</body>
</html>