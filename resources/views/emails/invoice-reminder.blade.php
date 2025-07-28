<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tagihan Sisa Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .order-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .order-info h3 {
            margin-top: 0;
            color: #007bff;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .payment-summary {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .payment-summary h4 {
            margin-top: 0;
            color: #495057;
        }
        .remaining-payment {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
            text-align: center;
        }
        .remaining-payment h4 {
            margin-top: 0;
            font-size: 18px;
        }
        .remaining-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tagihan Sisa Pembayaran</h1>
            <p>Pesanan {{ $order->nomor }}</p>
        </div>

        <div class="alert">
            <h4 style="margin-top: 0;">Pengingat Pembayaran</h4>
            <p style="margin-bottom: 0;">
                Kami ingin mengingatkan bahwa masih ada sisa pembayaran yang perlu diselesaikan untuk pesanan Anda.
            </p>
        </div>

        <div class="order-info">
            <h3>Detail Pesanan</h3>
            <div class="info-row">
                <span class="info-label">Nomor Pesanan:</span>
                <span class="info-value">{{ $order->nomor }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Customer:</span>
                <span class="info-value">{{ $order->user->nama ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Paket:</span>
                <span class="info-value">{{ $order->paket->nama ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Durasi:</span>
                <span class="info-value">{{ $order->durasi }} Bulan</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Pemesanan:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($order->tgl)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Batas Waktu Pembayaran:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($order->tgl_tempo)->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        <div class="payment-summary">
            <h4>Ringkasan Pembayaran</h4>
            <div class="info-row">
                <span class="info-label">Total Tagihan:</span>
                <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Dibayar:</span>
                <span class="info-value">Rp {{ number_format($order->payment->where('status', 'terima')->sum('jumlah'), 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="remaining-payment">
            <h4>Sisa Pembayaran Yang Harus Diselesaikan</h4>
            <div class="remaining-amount">
                Rp {{ number_format($order->total - $order->payment->where('status', 'terima')->sum('jumlah'), 0, ',', '.') }}
            </div>
        </div>

        <div style="background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #17a2b8;">
            <h4 style="margin-top: 0;">Cara Pembayaran</h4>
            <p style="margin-bottom: 0;">
                Silakan login ke akun Anda untuk melakukan pembayaran atau hubungi tim kami untuk informasi lebih lanjut mengenai metode pembayaran yang tersedia.
            </p>
        </div>

        <div class="footer">
            <p>Invoice terlampir dalam email ini.</p>
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
            <p><strong>Tim NEO Agency Advertising</strong></p>
        </div>
    </div>
</body>
</html>