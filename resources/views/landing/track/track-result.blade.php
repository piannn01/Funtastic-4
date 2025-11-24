@extends('landing.layout.app')

@section('content')

<div class="max-w-4xl mx-auto mt-24">
    <h2 class="text-3xl font-bold mb-6">Hasil Pencarian Pesanan</h2>

    @foreach ($orders as $o)
    <div class="bg-white shadow p-6 rounded-xl mb-6">

        <h3 class="text-xl font-bold">{{ $o->name }}</h3>

        <p class="text-gray-700">Layanan: <strong>{{ $o->service->name }}</strong></p>
        <p class="text-gray-700">Harga: Rp {{ number_format($o->price,0,',','.') }}</p>

        <p class="text-gray-700">
            Status Pembayaran:
            <span class="font-bold">
                {{ strtoupper($o->payment_status) }}
            </span>
        </p>

        <h4 class="mt-4 font-semibold">Progress Pengerjaan:</h4>

        <!-- PROGRESS BAR -->
        <div class="w-full bg-gray-200 rounded-full h-4 mt-2">
            <div class="bg-blue-600 h-4 rounded-full"
                 style="width: {{ $o->progress_percent ?? 0 }}%">
            </div>
        </div>

        <p class="mt-1 text-sm text-gray-600">
            {{ $o->progress_percent ?? 0 }}% selesai
        </p>

        @if($o->progress_note)
        <p class="mt-3 text-gray-700">
            <strong>Catatan:</strong> {{ $o->progress_note }}
        </p>
        @endif

    </div>
    @endforeach
</div>

@endsection
