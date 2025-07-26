<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->nomor }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .company-info {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        .invoice-info {
            display: table-cell;
            vertical-align: top;
            text-align: right;
            width: 50%;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .client-info {
            background: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #007bff;
            margin-bottom: 30px;
        }
        .client-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #007bff;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            width: 50%;
            margin-left: auto;
            margin-top: 20px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .total-label {
            display: table-cell;
            padding: 8px;
            font-weight: bold;
            text-align: right;
            width: 60%;
        }
        .total-value {
            display: table-cell;
            padding: 8px;
            text-align: right;
            width: 40%;
            border-bottom: 1px solid #ddd;
        }
        .grand-total {
            background: #007bff;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .payment-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 20px;
            margin-top: 30px;
        }
        .payment-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-belum {
            background: #f8d7da;
            color: #721c24;
        }
        .status-sebagian {
            background: #fff3cd;
            color: #856404;
        }
        .status-lunas {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="company-info">
            <img src="/images/logo.jpg" width="80pt"/>
            <div class="company-name">Neo Agency & Advertising</div>
            <div>Social Media Management Services</div>
            <div>Indonesia</div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">{{ $order->nomor }}</div>
            <div>Tanggal: {{ \Carbon\Carbon::parse($order->tgl)->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <div class="client-info">
        <div class="client-title">Tagihan Kepada:</div>
        <div><strong>{{ $order->user->name }}</strong></div>
        <div>{{ $order->user->email }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nomor Pesanan:</td>
            <td>{{ $order->nomor }}</td>
            <td class="info-label">Status Pembayaran:</td>
            <td>
                <span class="status-badge status-{{ strtolower(str_replace(' ', '', $order->status_pembayaran)) }}">
                    {{ $order->status_pembayaran }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Pemesanan:</td>
            <td>{{ \Carbon\Carbon::parse($order->tgl)->translatedFormat('d F Y') }}</td>
            <td class="info-label">Tanggal Selesai:</td>
            <td>{{ \Carbon\Carbon::parse($order->tgl_selesai)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Durasi:</td>
            <td>{{ $order->durasi }} Bulan</td>
            <td class="info-label">Batas Pembayaran:</td>
            <td>{{ \Carbon\Carbon::parse($order->tgl_tempo)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-center">Durasi</th>
                <th class="text-right">Harga/Bulan</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $order->paket->nama }}</strong>
                    <br>
                    <small>{{ $order->paket->deskripsi ?? 'Paket Social Media Management' }}</small>
                </td>
                <td class="text-center">{{ $order->durasi }} Bulan</td>
                <td class="text-right">Rp {{ number_format($order->harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div class="total-value">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Minimal DP (50%):</div>
            <div class="total-value">Rp {{ number_format($order->total * 0.5, 0, ',', '.') }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Total Dibayar:</div>
            <div class="total-value">Rp {{ number_format($order->payment->where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}</div>
        </div>
        <div class="total-row grand-total">
            <div class="total-label">Sisa Pembayaran:</div>
            <div class="total-value">Rp {{ number_format($order->total - $order->payment->where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}</div>
        </div>
    </div>

    @if($order->payment->count() > 0)
    <div style="margin-top: 40px;">
        <h3 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px;">Riwayat Pembayaran</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-right">Jumlah</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->payment as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d F Y') }}</td>
                    <td class="text-right">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $payment->status }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="payment-info">
        <div class="payment-title">Informasi Pembayaran:</div>
        <p>• Minimal pembayaran adalah 50% dari total tagihan</p>
        <p>• Pembayaran dapat dilakukan secara bertahap</p>
        <p>• Layanan akan dimulai setelah minimal DP diterima</p>
        <p>• Hubungi kami jika ada pertanyaan mengenai pembayaran</p>
    </div>

    <div class="footer">
        <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
        <p>Neo Agency & Advertising - Social Media Management Services</p>
        <p>Invoice ini dibuat secara otomatis pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>
</html>