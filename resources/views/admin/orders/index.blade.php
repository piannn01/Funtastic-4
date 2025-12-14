@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Daftar Pesanan</h1>

    {{-- ============================= --}}
    {{--   FILTER + SORT CONTROLS      --}}
    {{-- ============================= --}}
    <form id="filterForm" method="GET" action="{{ route('admin.orders.index') }}"
          class="bg-white shadow rounded-lg p-4 mb-4">

        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">

            {{-- Search --}}
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama / email / IG..."
                   class="border rounded px-3 py-2 w-full">

            {{-- Single Date Picker --}}
            <div>
                <input type="text" id="date_picker"
                       placeholder="Pilih tanggal..."
                       class="border rounded px-3 py-2 w-full"
                       readonly>

                {{-- hidden input tanggal untuk backend --}}
                <input type="hidden" name="date" id="date" value="{{ $date }}">
            </div>

            {{-- Service filter --}}
            <select name="service_id" class="border rounded px-3 py-2 w-full">
                <option value="">Semua Layanan</option>
                @foreach ($services as $srv)
                    <option value="{{ $srv->id }}"
                        {{ (string)$serviceId === (string)$srv->id ? 'selected' : '' }}>
                        {{ $srv->name }}
                    </option>
                @endforeach
            </select>

            {{-- Status filter --}}
            <select name="status" class="border rounded px-3 py-2 w-full">
                <option value="">Semua Status Order</option>
                <option value="pending"   {{ $status==='pending' ? 'selected' : '' }}>Pending</option>
                <option value="process"   {{ $status==='process' ? 'selected' : '' }}>Process</option>
                <option value="done"      {{ $status==='done' ? 'selected' : '' }}>Done</option>
                <option value="cancelled" {{ $status==='cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            {{-- Payment status filter --}}
            <select name="payment_status" class="border rounded px-3 py-2 w-full">
                <option value="">Semua Status Bayar</option>
                <option value="unpaid"  {{ $paymentStatus==='unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid"    {{ $paymentStatus==='paid' ? 'selected' : '' }}>Paid</option>
                <option value="expired" {{ $paymentStatus==='expired' ? 'selected' : '' }}>Expired</option>
                <option value="failed"  {{ $paymentStatus==='failed' ? 'selected' : '' }}>Failed</option>
            </select>

            {{-- Sort --}}
            <div class="flex gap-2">
                <select name="sort_by" class="border rounded px-3 py-2 w-full">
                    <option value="created_at" {{ $sortBy==='created_at' ? 'selected':'' }}>Tanggal</option>
                    <option value="name"       {{ $sortBy==='name' ? 'selected':'' }}>Nama</option>
                    <option value="email"      {{ $sortBy==='email' ? 'selected':'' }}>Email</option>
                    <option value="price"      {{ $sortBy==='price' ? 'selected':'' }}>Harga</option>
                    <option value="status"     {{ $sortBy==='status' ? 'selected':'' }}>Status Order</option>
                    <option value="payment_status" {{ $sortBy==='payment_status' ? 'selected':'' }}>Status Bayar</option>
                </select>

                <select name="sort_dir" class="border rounded px-3 py-2">
                    <option value="desc" {{ $sortDir==='desc' ? 'selected':'' }}>↓</option>
                    <option value="asc"  {{ $sortDir==='asc' ? 'selected':'' }}>↑</option>
                </select>
            </div>
        </div>

        <div class="mt-3 flex gap-2">
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Terapkan
            </button>

            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded">
                Reset
            </a>
        </div>
    </form>


    {{-- ============================= --}}
    {{--          TABLE WRAPPER       --}}
    {{-- ============================= --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="p-4 text-left whitespace-nowrap">Tanggal</th>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Layanan</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Bayar</th>
                    <th class="p-4 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $order)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="p-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $order->created_at?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="p-4 font-semibold">
                        {{ $order->name }}
                    </td>

                    <td class="p-4">
                        {{ $order->email }}
                    </td>

                    <td class="p-4">
                        {{ optional($order->service)->name ?? '-' }}
                    </td>

                    <td class="p-4">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $order->status==='done'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>

                    <td class="p-4">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $order->payment_status==='paid'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </td>

                    <td class="p-4">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                            Detail
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Belum ada pesanan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>

{{-- ============================= --}}
{{-- FLATPICKR SINGLE DATE SCRIPT  --}}
{{-- ============================= --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dateHidden = document.getElementById('date');
    const datePicker = document.getElementById('date_picker');

    // tampilkan nilai lama kalau sudah pernah filter
    if (dateHidden.value) {
        datePicker.value = dateHidden.value;
    }

    flatpickr("#date_picker", {
        dateFormat: "Y-m-d",
        defaultDate: dateHidden.value || null,

        onClose: function(selectedDates) {
            if (selectedDates.length === 1) {
                const d = selectedDates[0].toISOString().slice(0,10);
                dateHidden.value = d;
            } else {
                dateHidden.value = "";
            }
        }
    });
});
</script>
@endsection
