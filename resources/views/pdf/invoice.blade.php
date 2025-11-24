<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->invoice_code }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            font-size: 13px;
            color: #333;
        }

        .container {
            width: 100%;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 8px;
            font-size: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        .status {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 0;
            margin-bottom: 10px;
            border-radius: 6px;
            color: white;
        }

        .paid { background: #4CAF50; }
        .pending { background: #FFC107; color: #000; }
        .failed { background: #F44336; }

        table {
            width: 100%;
            margin-top: 6px;
        }

        td {
            padding: 4px 0;
        }

        .footer-note {
            margin-top: 15px;
            padding: 10px;
            background: #f5f5f5;
            border-left: 3px solid #1976d2;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>INVOICE PEMBAYARAN</h2>

    {{-- STATUS --}}
    @php
        $status = strtolower($order->payment_status);
    @endphp

    <div class="status 
        {{ $status === 'paid' ? 'paid' : ($status === 'pending' ? 'pending' : 'failed') }}">
        {{ strtoupper($order->payment_status) }}
    </div>

    <div class="section-title">Informasi Utama</div>

    <table>
        <tr><td><strong>Kode Unik:</strong></td><td>{{ $order->kode_unik }}</td></tr>
        <tr><td><strong>Kode Invoice:</strong></td><td>{{ $order->invoice_code }}</td></tr>
        <tr><td><strong>Tanggal:</strong></td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>

    <div class="section-title">Data Pemesan</div>

    <table>
        <tr><td><strong>Nama:</strong></td><td>{{ $order->name }}</td></tr>
        <tr><td><strong>Email:</strong></td><td>{{ $order->email }}</td></tr>
        <tr><td><strong>No. WA:</strong></td><td>{{ $order->phone }}</td></tr>
        <tr><td><strong>Instagram:</strong></td><td>{{ $order->instagram ?? '-' }}</td></tr>
    </table>

    <div class="section-title">Detail Layanan</div>

    <table>
        <tr><td><strong>Paket:</strong></td><td>{{ $order->service->name }}</td></tr>
        <tr><td><strong>Harga:</strong></td>
            <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer-note">
        ⚠ Harap simpan invoice ini sebagai bukti pembayaran dan akses layanan Anda.
        Kode Unik dibutuhkan untuk membuka halaman <strong>Cek Pesanan</strong>.
    </div>

</div>

</body>
</html>
