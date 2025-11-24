@extends('landing.layout.app')

@section('content')

<div class="max-w-lg mx-auto mt-24 bg-white shadow-lg p-8 rounded-2xl">
    <h2 class="text-2xl font-bold text-center mb-6">Cek Status Pesanan</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('cekpesanan.check') }}">
        @csrf

        <label class="font-semibold">Masukkan Email Anda:</label>
        <input type="email" name="email"
               class="w-full mt-2 p-3 border rounded-lg"
               placeholder="email@example.com" required>

        <button class="w-full bg-blue-600 text-white p-3 rounded-lg mt-5 hover:bg-blue-700">
            Cek Pesanan
        </button>
    </form>
</div>

@endsection
