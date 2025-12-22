@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Laporan Keseluruhan Sistem</h1>
            <p class="text-gray-600 mt-1">
                Ringkasan manajerial untuk owner (keuangan, layanan, pesanan, progres, dan testimoni).
            </p>
        </div>

        <a href="{{ route('admin.reports.summary.download', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
            Download PDF
        </a>
    </div>

    {{-- Filter Periode --}}
    <div class="bg-white rounded-xl shadow p-5 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="text-sm text-gray-600">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="w-full border rounded-lg px-3 py-2 mt-1">
            </div>

            <div class="flex gap-2">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
                    Terapkan
                </button>

                <a href="{{ route('admin.reports.summary') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold px-4 py-2 rounded-lg">
                    Reset
                </a>
            </div>

            <div class="text-sm text-gray-500">
                Periode:
                <span class="font-semibold">
                    {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}
                </span>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Pesanan</p>
            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-500 mt-1">Selesai: {{ $completedOrders }} | Belum: {{ $unfinishedOrders }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pembayaran</p>
            <p class="text-2xl font-bold text-green-600">Paid: {{ $paidOrders }}</p>
            <p class="text-xs text-gray-500 mt-1">Pending: {{ $pendingOrders }} | Failed: {{ $failedOrders }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Pendapatan (Paid)</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Periode terpilih</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Layanan & Testimoni</p>
            <p class="text-2xl font-bold">{{ $totalServices }} layanan</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $totalTestimonials }} testimoni | Avg rating: {{ $avgRating }}
            </p>
        </div>

    </div>

    {{-- Top Layanan --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">🏆 Top Layanan Terlaris (Paid)</h2>

        @if($topServices->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-3">Layanan</th>
                            <th class="py-2 pr-3">Total Order</th>
                            <th class="py-2 pr-3">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topServices as $row)
                            <tr class="border-b">
                                <td class="py-3 pr-3 font-semibold">{{ optional($row->service)->name ?? '-' }}</td>
                                <td class="py-3 pr-3">{{ $row->total_orders }}</td>
                                <td class="py-3 pr-3">Rp {{ number_format($row->total_income, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">Belum ada transaksi paid pada periode ini.</p>
        @endif
    </div>

    {{-- Tanggungan --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">📌 Tanggungan Pekerjaan (Paid, belum selesai)</h2>

        @if($unfinishedPaidOrders->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-3">Kode Unik</th>
                            <th class="py-2 pr-3">Klien</th>
                            <th class="py-2 pr-3">Layanan</th>
                            <th class="py-2 pr-3">Progress</th>
                            <th class="py-2 pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unfinishedPaidOrders as $o)
                            @php $p = $o->progress ?? 0; @endphp
                            <tr class="border-b">
                                <td class="py-3 pr-3 font-semibold">{{ $o->kode_unik ?? '-' }}</td>
                                <td class="py-3 pr-3">
                                    <div class="font-medium">{{ $o->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $o->email }}</div>
                                </td>
                                <td class="py-3 pr-3">{{ optional($o->service)->name ?? '-' }}</td>
                                <td class="py-3 pr-3">
                                    <div class="w-44 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2" style="width: {{ $p }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">{{ $p }}%</div>
                                    @if(!empty($o->progress_note))
                                        <div class="text-xs text-gray-500 mt-1">Catatan: {{ $o->progress_note }}</div>
                                    @endif
                                </td>
                                <td class="py-3 pr-3">
                                    <a class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg"
                                       href="{{ route('admin.orders.show', $o->id) }}">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-green-700 font-semibold">✅ Tidak ada tanggungan pada periode ini.</p>
        @endif
    </div>

    {{-- Transaksi Paid Terbaru --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">💰 Transaksi Paid Terbaru</h2>

        @if($recentPaidOrders->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-3">Tanggal</th>
                            <th class="py-2 pr-3">Invoice</th>
                            <th class="py-2 pr-3">Klien</th>
                            <th class="py-2 pr-3">Layanan</th>
                            <th class="py-2 pr-3">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPaidOrders as $o)
                            <tr class="border-b">
                                <td class="py-3 pr-3">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 pr-3 font-semibold">{{ $o->invoice_code }}</td>
                                <td class="py-3 pr-3">{{ $o->name }}</td>
                                <td class="py-3 pr-3">{{ optional($o->service)->name ?? '-' }}</td>
                                <td class="py-3 pr-3">Rp {{ number_format($o->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">Belum ada transaksi paid pada periode ini.</p>
        @endif
    </div>

</div>
@endsection
