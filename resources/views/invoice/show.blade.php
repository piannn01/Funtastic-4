@extends('landing.layout.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #0d47a1, #1976d2);
        min-height: 100vh;
        padding-bottom: 40px;
    }

    .glass-card {
        backdrop-filter: blur(14px);
        background: rgba(255, 255, 255, 0.18);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.28);
        padding: 40px 35px;
        color: #fff;
        box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .glass-title {
        font-size: 30px;
        font-weight: 800;
        text-align: center;
        margin-bottom: 15px;
        letter-spacing: 1px;
    }

    .status-badge {
        padding: 8px 25px;
        font-size: 14px;
        font-weight: 700;
        display: inline-block;
        border-radius: 30px;
        letter-spacing: 1.2px;
        margin-bottom: 10px;
    }

    .badge-paid {
        background: rgba(46, 204, 113, 0.22);
        border: 2px solid #2ecc71;
        color: #2ecc71;
    }

    .badge-pending {
        background: rgba(241, 196, 15, 0.25);
        border: 2px solid #f1c40f;
        color: #f1c40f;
    }

    .badge-failed {
        background: rgba(231, 76, 60, 0.25);
        border: 2px solid #e74c3c;
        color: #e74c3c;
    }

    .section-title {
        font-weight: 700;
        margin-top: 25px;
        margin-bottom: 10px;
        font-size: 18px;
        border-bottom: 1px solid rgba(255,255,255,0.25);
        padding-bottom: 6px;
    }

    .alert-info {
        background: rgba(255, 255, 255, 0.30);
        padding: 12px 16px;
        border-left: 4px solid #fff;
        border-radius: 10px;
        margin-top: 18px;
        font-size: 14px;
        backdrop-filter: blur(5px);
    }

    .btn-download {
        background: #4CAF50;
        color: #fff;
        padding: 14px 24px;
        border-radius: 10px;
        display: inline-block;
        margin-top: 25px;
        font-size: 15px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.2s ease;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    }

    .btn-download:hover {
        background: #43a047;
        transform: translateY(-2px);
    }

    p {
        font-size: 15px;
        margin-bottom: 6px;
    }
</style>


<div class="container mx-auto px-6 py-12">

    <div class="glass-card max-w-2xl mx-auto">

        <h2 class="glass-title">INVOICE PEMBAYARAN</h2>

        {{-- STATUS BADGE --}}
        @php
            $status = strtolower($order->payment_status);
        @endphp

        <div style="text-align:center; margin-bottom: 10px;">
            @if ($status === 'paid' || $status === 'lunas')
                <span class="status-badge badge-paid">PAID</span>
            @elseif ($status === 'pending')
                <span class="status-badge badge-pending">PENDING</span>
            @else
                <span class="status-badge badge-failed">FAILED</span>
            @endif
        </div>

        {{-- INFORMASI UTAMA --}}
        <p><strong>Kode Unik Akses:</strong> {{ $order->kode_unik }}</p>
        <p><strong>Kode Invoice:</strong> {{ $order->invoice_code }}</p>
        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

        <div class="alert-info">
            ⚠️ <strong>Simpan invoice ini baik-baik.</strong><br>
            Kode unik digunakan untuk mengakses halaman <strong>Cek Pesanan</strong> dan melihat progres layanan.
        </div>

        {{-- DATA PEMESAN --}}
        <h3 class="section-title">Data Pemesan</h3>
        <p><strong>Nama:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>No. WA:</strong> {{ $order->phone }}</p>
        <p><strong>Instagram:</strong> {{ $order->instagram ?? '-' }}</p>

        {{-- DATA LAYANAN --}}
        <h3 class="section-title">Paket Layanan</h3>
        <p><strong>Paket:</strong> {{ $order->service->name }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>

        {{-- BUTTON DOWNLOAD --}}
        <div style="text-align: center;">
            <a href="{{ route('invoice.download', $order->invoice_code) }}" class="btn-download">
                Download Invoice (PDF)
            </a>
        </div>

    </div>

</div>

@endsection
