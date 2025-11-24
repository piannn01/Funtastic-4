@extends('admin.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">
        Selamat Datang di Dashboard Admin 🎉
    </h1>

    {{-- CARD STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        {{-- Total Layanan --}}
        <div class="bg-blue-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <p class="text-lg opacity-80 mb-1">TOTAL LAYANAN</p>
            <p class="text-4xl font-bold">{{ $totalServices }}</p>
        </div>

        {{-- Total Pesanan --}}
        <div class="bg-green-500 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <p class="text-lg opacity-80 mb-1">TOTAL PESANAN</p>
            <p class="text-4xl font-bold">{{ $totalOrders }}</p>
        </div>

        {{-- Total Testimoni --}}
        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <p class="text-lg opacity-80 mb-1">TOTAL TESTIMONI</p>
            <p class="text-4xl font-bold">{{ $totalTestimonials }}</p>
        </div>

    </div>

    {{-- Informasi --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-2">Statistik Umum</h2>
        <p class="text-gray-600">
            Gunakan menu di atas untuk mengelola layanan, pesanan, dan testimoni.
        </p>
    </div>

</div>

@endsection
