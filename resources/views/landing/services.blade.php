{{-- resources/views/landing/services.blade.php --}}
@extends('landing.layout.app')

@section('content')

<div class="pt-24"> {{-- MENAMBAH JARAK DARI NAVBAR --}}

    {{-- Judul Halaman --}}
    <h2 class="text-3xl font-extrabold text-center mb-12">
        Paket Layanan
    </h2>

    {{-- CARD LIST LAYANAN --}}
    <div class="grid md:grid-cols-3 gap-8 mb-20 px-4">

        @foreach ($services as $service)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-2xl hover:-translate-y-1 transition">

                {{-- Nama Paket --}}
                <h3 class="text-2xl font-bold text-center mb-4">{{ $service->name }}</h3>

                {{-- Detail --}}
                <div class="text-sm text-gray-700 space-y-2 mb-6">
                    <p><strong>Feed:</strong> {{ $service->feed ?: '-' }}</p>
                    <p><strong>Stories:</strong> {{ $service->stories ?: '-' }}</p>
                    <p><strong>Video Reels:</strong> {{ $service->video_reels ?: '-' }}</p>
                    <p><strong>Durasi:</strong> {{ $service->duration ?: '-' }}</p>
                </div>

                {{-- Harga --}}
                <p class="text-3xl font-extrabold text-blue-600 text-center mb-8">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                </p>

                {{-- Tombol Pesan --}}
                <a href="{{ route('order.form', $service->id) }}"
                    class="block text-center bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                    Pesan Sekarang
                </a>
            </div>
        @endforeach

    </div>

    {{-- PERBANDINGAN PAKET --}}
    <h2 class="text-3xl font-extrabold text-center mt-10 mb-8">
        Perbandingan Paket Layanan
    </h2>

    <div class="overflow-x-auto px-4 pb-20">
        <table class="table-auto w-full border-collapse border border-gray-300 text-center bg-white rounded-lg shadow">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="p-4 border border-gray-300">Fitur</th>
                    @foreach ($services as $service)
                        <th class="p-4 border border-gray-300">{{ $service->name }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>

                {{-- Feed --}}
                <tr>
                    <td class="p-4 border">Feed</td>
                    @foreach ($services as $service)
                        <td class="p-4 border">
                            @if($service->feed > 0)
                                {{ $service->feed }}x
                            @else
                                <span class="text-red-500 font-bold">✕</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- Stories --}}
                <tr>
                    <td class="p-4 border">Stories</td>
                    @foreach ($services as $service)
                        <td class="p-4 border">
                            @if($service->stories > 0)
                                {{ $service->stories }}x
                            @else
                                <span class="text-red-500 font-bold">✕</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- Video Reels --}}
                <tr>
                    <td class="p-4 border">Video Reels</td>
                    @foreach ($services as $service)
                        <td class="p-4 border">
                            @if($service->video_reels > 0)
                                {{ $service->video_reels }}x
                            @else
                                <span class="text-red-500 font-bold">✕</span>
                            @endif
                        </td>
                    @endforeach
                </tr>

                {{-- Durasi --}}
                <tr>
                    <td class="p-4 border">Durasi</td>
                    @foreach ($services as $service)
                        <td class="p-4 border">{{ $service->duration ?: '-' }}</td>
                    @endforeach
                </tr>

                {{-- Harga --}}
                <tr class="bg-gray-50 font-bold">
                    <td class="p-4 border">Harga</td>
                    @foreach ($services as $service)
                        <td class="p-4 border text-blue-600">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </td>
                    @endforeach
                </tr>

            </tbody>
        </table>
    </div>

</div>

@endsection
