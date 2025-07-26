<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Baru</title>
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
            <h1>Terima Kasih atas Pesanan Anda!</h1>
            <p>Pesanan Anda telah berhasil dibuat dan sedang diproses.</p>
        </div>

        <div class="order-info">
            <h3>Detail Pesanan</h3>
            <div class="info-row">
                <span class="info-label">Nomor Pesanan:</span>
                <span class="info-value">{{ $order->nomor }}</span>
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
                <span class="info-label">Harga per Bulan:</span>
                <span class="info-value">Rp {{ number_format($order->harga, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Harga:</span>
                <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Pemesanan:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($order->tgl)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Selesai:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($order->tgl_selesai)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Batas Waktu Pembayaran:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($order->tgl_tempo)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status Pembayaran:</span>
                <span class="info-value">{{ $order->status_pembayaran }}</span>
            </div>
        </div>

        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;">
            <h4 style="margin-top: 0; color: #856404;">Informasi Pembayaran</h4>
            <p style="margin-bottom: 0; color: #856404;">
                Silakan lakukan pembayaran minimal DP (50% dari total) sebelum tanggal tempo untuk mengaktifkan layanan Anda.
                Minimal pembayaran: <strong>Rp {{ number_format($order->total * 0.5, 0, ',', '.') }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
            <p><strong>Tim Kalaman Multimedia Karya</strong></p>
        </div>
    </div>
</body>
</html>