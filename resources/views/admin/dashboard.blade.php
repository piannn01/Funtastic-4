@extends('admin.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Dashboard Admin 👋</h1>
            <p class="text-gray-600 mt-1">
                Pantau tanggungan klien dan progres pengerjaan tim secara real-time.
            </p>
        </div>

        {{-- Completion Rate --}}
        <div class="bg-white rounded-xl shadow px-6 py-4 w-full md:w-[320px]">
            <p class="text-sm text-gray-500 mb-2">Tingkat Penyelesaian Pesanan (Paid)</p>
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="bg-green-500 h-3" style="width: {{ $completionRate }}%"></div>
            </div>
            <div class="flex items-center justify-between mt-2">
                <p class="text-sm font-semibold">{{ $completionRate }}%</p>
                <p class="text-xs text-gray-500">Semakin tinggi semakin baik</p>
            </div>
        </div>
    </div>

    {{-- ALERT TANGGUNGAN --}}
    @if($unfinishedPaidOrdersCount > 0)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 rounded-xl p-4 mb-6">
            <p class="font-bold">⚠️ Ada tanggungan pekerjaan!</p>
            <p class="text-sm mt-1">
                Terdapat <span class="font-semibold">{{ $unfinishedPaidOrdersCount }}</span> pesanan klien yang sudah
                <span class="font-semibold">dibayar</span> namun belum <span class="font-semibold">selesai</span>.
                Prioritaskan pesanan ini agar pelayanan tepat waktu.
            </p>
        </div>
    @else
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-6">
            <p class="font-bold">✅ Mantap!</p>
            <p class="text-sm mt-1">Semua pesanan berstatus paid sudah diselesaikan.</p>
        </div>
    @endif

    {{-- CARD STATISTIK UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg">
            <p class="text-lg opacity-80 mb-1">TOTAL LAYANAN</p>
            <p class="text-4xl font-bold">{{ $totalServices }}</p>
        </div>

        <div class="bg-green-500 text-white p-6 rounded-xl shadow-lg">
            <p class="text-lg opacity-80 mb-1">TOTAL PESANAN</p>
            <p class="text-4xl font-bold">{{ $totalOrders }}</p>
        </div>

        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg">
            <p class="text-lg opacity-80 mb-1">TOTAL TESTIMONI</p>
            <p class="text-4xl font-bold">{{ $totalTestimonials }}</p>
        </div>

    </div>

    {{-- CARD OPERASIONAL --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 mb-1">Pesanan Paid</p>
            <p class="text-2xl font-bold text-green-600">{{ $paidOrders }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 mb-1">Menunggu Pembayaran</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingPaymentOrders }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 mb-1">Pembayaran Gagal</p>
            <p class="text-2xl font-bold text-red-600">{{ $failedPaymentOrders }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-sm text-gray-500 mb-1">Tanggungan (Paid, belum selesai)</p>
            <p class="text-2xl font-bold text-gray-900">{{ $unfinishedPaidOrdersCount }}</p>
        </div>

    </div>

    {{-- TABEL TANGGUNGAN --}}
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
            <h2 class="text-xl font-bold">📌 Tanggungan Pekerjaan</h2>
            <p class="text-sm text-gray-500">Ditampilkan: pesanan paid namun belum completed</p>
        </div>

        @if($unfinishedPaidOrders->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-3">Kode Unik</th>
                            <th class="py-2 pr-3">Klien</th>
                            <th class="py-2 pr-3">Layanan</th>
                            <th class="py-2 pr-3">Progress</th>
                            <th class="py-2 pr-3">Status</th>
                            <th class="py-2 pr-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unfinishedPaidOrders as $order)
                            @php
                                // ✅ FIX: gunakan field progress asli dari tabel orders
                                $progress = (int) ($order->progress ?? 0);

                                // jaga-jaga biar tidak aneh
                                if ($progress < 0) $progress = 0;
                                if ($progress > 100) $progress = 100;

                                $status = $order->status ?? '-';
                            @endphp
                            <tr class="border-b">
                                <td class="py-3 pr-3 font-semibold">
                                    {{ $order->kode_unik ?? '-' }}
                                </td>

                                <td class="py-3 pr-3">
                                    <div class="font-medium">{{ $order->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->email }}</div>
                                </td>

                                <td class="py-3 pr-3">
                                    {{ optional($order->service)->name ?? '-' }}
                                </td>

                                <td class="py-3 pr-3">
                                    <div class="w-44 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">{{ $progress }}%</div>

                                    @if(!empty($order->progress_note))
                                        <div class="text-xs text-gray-500 mt-1">
                                            Catatan: {{ $order->progress_note }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-3 pr-3">
                                    @if($status === 'processing')
                                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">
                                            Processing
                                        </span>
                                    @elseif($status === 'completed')
                                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">
                                            Completed
                                        </span>
                                    @elseif($status === 'cancelled')
                                        <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800 font-semibold">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700 font-semibold">
                                            {{ ucfirst($status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 pr-3">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 rounded-lg">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-green-700 font-semibold">✅ Tidak ada tanggungan. Semua pesanan paid sudah selesai.</p>
        @endif
    </div>

    {{-- PESANAN TERBARU --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">🕒 Pesanan Terbaru</h2>

        @if($recentOrders->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-3">Invoice</th>
                            <th class="py-2 pr-3">Klien</th>
                            <th class="py-2 pr-3">Layanan</th>
                            <th class="py-2 pr-3">Pembayaran</th>
                            <th class="py-2 pr-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            @php $ps = $order->payment_status ?? 'pending'; @endphp
                            <tr class="border-b">
                                <td class="py-3 pr-3 font-semibold">{{ $order->invoice_code }}</td>
                                <td class="py-3 pr-3">{{ $order->name }}</td>
                                <td class="py-3 pr-3">{{ optional($order->service)->name ?? '-' }}</td>
                                <td class="py-3 pr-3">
                                    @if($ps === 'paid')
                                        <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">PAID</span>
                                    @elseif($ps === 'failed')
                                        <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800 font-semibold">FAILED</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">PENDING</span>
                                    @endif
                                </td>
                                <td class="py-3 pr-3">
                                    <span class="text-xs font-semibold">{{ strtoupper($order->status ?? '-') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">Belum ada pesanan.</p>
        @endif
    </div>

</div>

@endsection
