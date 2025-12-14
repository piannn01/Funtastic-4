@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-6">
        <h1 class="text-3xl font-bold">Laporan Keuangan</h1>
        <div class="text-sm text-gray-500">
            Periode: {{ $from->format('d M Y') }} - {{ $to->format('d M Y') }}
        </div>
    </div>

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('admin.reports.finance') }}"
          class="bg-white shadow rounded-lg p-4 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="text-xs text-gray-500">Dari tanggal</label>
                <input type="date" name="from" value="{{ $from->toDateString() }}"
                       class="border rounded px-3 py-2 w-full">
            </div>

            <div>
                <label class="text-xs text-gray-500">Sampai tanggal</label>
                <input type="date" name="to" value="{{ $to->toDateString() }}"
                       class="border rounded px-3 py-2 w-full">
            </div>

            <div>
                <label class="text-xs text-gray-500">Layanan</label>
                <select name="service_id" class="border rounded px-3 py-2 w-full">
                    <option value="">Semua Layanan</option>
                    @foreach($services as $srv)
                        <option value="{{ $srv->id }}"
                            {{ (string)$serviceId === (string)$srv->id ? 'selected' : '' }}>
                            {{ $srv->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">Status Pembayaran</label>
                <select name="payment_status" class="border rounded px-3 py-2 w-full">
                    <option value="">Semua Status Bayar</option>
                    <option value="paid"   {{ $paymentStatus==='paid' ? 'selected':'' }}>Paid</option>
                    <option value="unpaid" {{ $paymentStatus==='unpaid' ? 'selected':'' }}>Unpaid</option>
                    <option value="failed" {{ $paymentStatus==='failed' ? 'selected':'' }}>Failed</option>
                    <option value="expired"{{ $paymentStatus==='expired' ? 'selected':'' }}>Expired</option>
                </select>
            </div>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Terapkan
            </button>
            <a href="{{ route('admin.reports.finance') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded">
                Reset
            </a>
        </div>
    </form>


    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Total Pendapatan (Paid)</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Pesanan Paid</p>
            <p class="text-2xl font-bold">{{ $totalPaidOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Total Pesanan</p>
            <p class="text-2xl font-bold">{{ $totalAllOrders }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Rata-rata Transaksi</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($avgOrderValue, 0, ',', '.') }}
            </p>
        </div>
    </div>


    {{-- REVENUE BY SERVICE --}}
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <h2 class="font-semibold text-lg mb-3">Pendapatan per Layanan</h2>

        @forelse($revenueByService as $row)
            <div class="flex items-center justify-between border-b py-2">
                <span class="text-gray-700">
                    {{ optional($row->service)->name ?? 'Layanan tidak ditemukan' }}
                </span>
                <span class="font-semibold">
                    Rp {{ number_format($row->total, 0, ',', '.') }}
                </span>
            </div>
        @empty
            <p class="text-gray-500">Belum ada pendapatan pada periode ini.</p>
        @endforelse
    </div>


    {{-- CHART --}}
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <h2 class="font-semibold text-lg mb-3">Grafik Pendapatan per Bulan</h2>

        @if($monthlyRevenue->count() > 0)
            <canvas id="revenueChart" height="80"></canvas>
        @else
            <p class="text-gray-500">Belum ada data grafik untuk periode ini.</p>
        @endif
    </div>


    {{-- TABLE DETAIL --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Layanan</th>
                        <th class="p-4 text-left">Harga</th>
                        <th class="p-4 text-left">Status Bayar</th>
                    </tr>
                </thead>

                <tbody>
                    @php $tableTotal = 0; @endphp

                    @forelse($orders as $order)
                        @php
                            // ganti ke total_price/harga kalau di DB kamu beda
                            $price = $order->price ?? 0;
                            $tableTotal += ($order->payment_status === 'paid') ? $price : 0;
                        @endphp

                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                {{ $order->created_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="p-4 font-medium">
                                {{ $order->name ?? '-' }}
                            </td>
                            <td class="p-4">
                                {{ $order->email ?? '-' }}
                            </td>
                            <td class="p-4">
                                {{ optional($order->service)->name ?? '-' }}
                            </td>
                            <td class="p-4">
                                Rp {{ number_format($price, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $order->payment_status==='paid'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700' }}">
                                    {{ strtoupper($order->payment_status ?? '-') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                Tidak ada data transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- FOOTER TOTAL --}}
                @if($orders->count() > 0)
                <tfoot>
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="4" class="p-4 text-right">Total Pendapatan Paid (Tabel):</td>
                        <td colspan="2" class="p-4">
                            Rp {{ number_format($tableTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>


{{-- Chart.js --}}
@if($monthlyRevenue->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthly = @json($monthlyRevenue);
    const labels = monthly.map(i => i.month);
    const totals = monthly.map(i => Number(i.total));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Pendapatan (Paid)',
                data: totals,
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    ticks: {
                        callback: (val) => 'Rp ' + Number(val).toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
