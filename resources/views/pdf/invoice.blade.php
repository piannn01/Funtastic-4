<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->invoice_code }}</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            font-size: 13px;
            color: #333;
        }

        .container{
            width: 100%;
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 10px;
        }

        /* ===== HEADER/KOP ===== */
        .header{
            width: 100%;
            margin-bottom: 12px;
        }

        .header-table{
            width: 100%;
            border-collapse: collapse;
        }

        .header-left{
            width: 45%;
            vertical-align: middle;
        }

        .header-right{
            width: 55%;
            vertical-align: middle;
            text-align: right;
        }

        .logo{
            height: 55px;           /* kunci biar gak gede */
            width: auto;
            display: inline-block;
        }

        .company{
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .tagline{
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #666;
        }

        .line{
            border-top: 1px solid #ddd;
            margin: 10px 0 14px 0;
        }

        h2{
            text-align: center;
            font-size: 20px;
            margin: 0 0 12px 0;
            letter-spacing: 0.5px;
        }

        /* ===== STATUS ===== */
        .status{
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            padding: 9px 0;
            margin-bottom: 12px;
            border-radius: 6px;
            color: #fff;
        }
        .paid{ background: #4CAF50; }
        .pending{ background: #FFC107; color:#000; }
        .failed{ background: #F44336; }

        /* ===== SECTION ===== */
        .section-title{
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 6px;
            font-size: 14px;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        td{
            padding: 5px 0;
            vertical-align: top;
        }

        td:first-child{
            width: 32%;
            color: #111;
        }

        /* biar nilai terlihat rapi */
        .value{
            text-align: right;
            font-weight: normal;
            color: #333;
        }

        .footer-note{
            margin-top: 14px;
            padding: 10px;
            background: #f7f7f7;
            border-left: 3px solid #1976d2;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
<div class="container">

    {{-- ===== KOP PERUSAHAAN (RAPI) ===== --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <img class="logo" src="{{ public_path('assets/Funtastic4.png') }}" alt="Funtastic 4">
                </td>
                <td class="header-right">
                    <p class="company">FUNTASTIC 4</p>
                    <p class="tagline">Jasa Pengelola Konten Media Sosial</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="line"></div>

    <h2>INVOICE PEMBAYARAN</h2>

    {{-- STATUS --}}
    @php
        $status = strtolower($order->payment_status ?? 'pending');
        $statusClass = $status === 'paid' ? 'paid' : ($status === 'pending' ? 'pending' : 'failed');
    @endphp

    <div class="status {{ $statusClass }}">
        {{ strtoupper($order->payment_status ?? 'PENDING') }}
    </div>

    <div class="section-title">Informasi Utama</div>
    <table>
        <tr>
            <td><strong>Kode Unik</strong></td>
            <td class="value">{{ $order->kode_unik }}</td>
        </tr>
        <tr>
            <td><strong>Kode Invoice</strong></td>
            <td class="value">{{ $order->invoice_code }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td class="value">{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="section-title">Data Pemesan</div>
    <table>
        <tr>
            <td><strong>Nama</strong></td>
            <td class="value">{{ $order->name }}</td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td class="value">{{ $order->email }}</td>
        </tr>
        <tr>
            <td><strong>No. WA</strong></td>
            <td class="value">{{ $order->phone }}</td>
        </tr>
        <tr>
            <td><strong>Instagram</strong></td>
            <td class="value">{{ $order->instagram ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Layanan</div>
    <table>
        <tr>
            <td><strong>Paket</strong></td>
            <td class="value">{{ optional($order->service)->name }}</td>
        </tr>
        <tr>
            <td><strong>Harga</strong></td>
            <td class="value">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer-note">
        ⚠ Harap simpan invoice ini sebagai bukti pembayaran dan akses layanan Anda.
        Kode Unik dibutuhkan untuk membuka halaman <strong>Cek Pesanan</strong>.
    </div>

</div>
</body>
</html>
