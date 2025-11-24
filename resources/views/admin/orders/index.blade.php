@extends('admin.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- ============================= --}}
    {{--          HEADER PAGE         --}}
    {{-- ============================= --}}
    <h1 class="text-3xl font-bold mb-6">Daftar Pesanan</h1>

    {{-- ============================= --}}
    {{--          TABLE WRAPPER       --}}
    {{-- ============================= --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full border-collapse">
            
            {{-- ============================= --}}
            {{--          TABLE HEAD          --}}
            {{-- ============================= --}}
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Layanan</th>
                    <th class="p-4 text-left">Aksi</th>
                </tr>
            </thead>

            {{-- ============================= --}}
            {{--          TABLE BODY          --}}
            {{-- ============================= --}}
            <tbody>

                @forelse ($orders as $order)
                <tr class="border-b hover:bg-gray-50 transition">

                    {{-- Nama --}}
                    <td class="p-4 font-semibold">
                        {{ $order->name }}
                    </td>

                    {{-- Email --}}
                    <td class="p-4">
                        {{ $order->email }}
                    </td>

                    {{-- Layanan --}}
                    <td class="p-4">
                        {{ $order->service->name }}
                    </td>

                    {{-- Aksi --}}
                    <td class="p-4">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                            Detail
                        </a>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">
                        Belum ada pesanan.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

</div>

@endsection
