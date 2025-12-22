<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keseluruhan Sistem</title>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        .box{
            border: 1px solid #ddd;
            padding: 18px;
            border-radius: 8px;
        }
        .header{
            width: 100%;
            margin-bottom: 10px;
        }
        .header table{
            width: 100%;
            border-collapse: collapse;
        }
        .logo{
            height: 55px;
            width: auto;
        }
        .company{
            text-align: right;
        }
        .company h2{
            margin: 0;
            font-size: 16px;
        }
        .company p{
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #666;
        }
        .line{
            border-top: 1px solid #ddd;
            margin: 10px 0 14px 0;
        }
        .title{
            text-align: center;
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .period{
            text-align: center;
            margin-bottom: 14px;
            font-size: 11px;
            color: #555;
        }

        .section-title{
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 6px;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        th, td{
            border: 1px solid #eee;
            padding: 6px;
            vertical-align: top;
        }
        th{
            background: #f5f5f5;
            text-align: left;
        }

        .grid{
            width: 100%;
            margin-top: 6px;
        }
        .grid td{
            border: none;
            padding: 2px 0;
        }

        .note{
            margin-top: 12px;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="box">

    <div class="header">
        <table>
            <tr>
                <td style="width:45%;">
                    <img class="logo" src="{{ public_path('assets/img/funtastic4-kop.png') }}" alt="Funtastic 4">
                </td>
                <td class="company" style="width:55%;">
                    <h2>FUNTASTIC 4</h2>
                    <p>Social Media Management</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="line"></div>

    <div class="title">LAPORAN KESELURUHAN SISTEM</div>
    <div class="period">
        Periode: {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }} |
        Dicetak: {{ now()->format('d/m/Y H:i') }}
    </div>

    <div class="section-title">Ringkasan Eksekutif</div>
    <table class="grid">
        <tr><td><strong>Total Pesanan</strong></td><td>{{ $totalOrders }}</td></tr>
        <tr><td><strong>Paid / Pending / Failed</strong></td><td>{{ $paidOrders }} / {{ $pendingOrders }} / {{ $failedOrders }}</td></tr>
        <tr><td><strong>Selesai / Belum Selesai</strong></td><td>{{ $completedOrders }} / {{ $unfinishedOrders }}</td></tr>
        <tr><td><strong>Total Pendapatan (Paid)</strong></td><td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td></tr>
        <tr><td><strong>Total Layanan</strong></td><td>{{ $totalServices }}</td></tr>
        <tr><td><strong>Testimoni (Avg Rating)</strong></td><td>{{ $totalTestimonials }} ({{ $avgRating }})</td></tr>
    </table>

    <div class="section-title">Top Layanan Terlaris (Paid)</div>
    @if($topServices->count())
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Total Order</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topServices as $row)
                    <tr>
                        <td>{{ optional($row->service)->name ?? '-' }}</td>
                        <td>{{ $row->total_orders }}</td>
                        <td>Rp {{ number_format($row->total_income, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="note">Tidak ada transaksi paid pada periode ini.</p>
    @endif

    <div class="section-title">Tanggungan Pekerjaan (Paid, belum selesai)</div>
    @if($unfinishedPaidOrders->count())
        <table>
            <thead>
                <tr>
                    <th>Kode Unik</th>
                    <th>Klien</th>
                    <th>Layanan</th>
                    <th>Progress</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unfinishedPaidOrders as $o)
                    <tr>
                        <td>{{ $o->kode_unik ?? '-' }}</td>
                        <td>
                            {{ $o->name }}<br>
                            <small>{{ $o->email }}</small>
                        </td>
                        <td>{{ optional($o->service)->name ?? '-' }}</td>
                        <td>{{ $o->progress ?? 0 }}%</td>
                        <td>{{ $o->progress_note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="note">Tidak ada tanggungan pada periode ini.</p>
    @endif


    <div class="section-title">Transaksi Paid Terbaru</div>
    @if($recentPaidOrders->count())
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Unik</th>
                    <th>Klien</th>
                    <th>Layanan</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentPaidOrders as $o)
                    <tr>
                        <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $o->kode_unik ?? '-' }}</td>
                        <td>{{ $o->name }}</td>
                        <td>{{ optional($o->service)->name ?? '-' }}</td>
                        <td>Rp {{ number_format($o->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="note">Tidak ada transaksi paid pada periode ini.</p>
    @endif

    <p class="note">
        Dokumen ini merupakan output sistem informasi sebagai bahan monitoring dan evaluasi kinerja perusahaan.
    </p>

</div>

</body>
</html>
