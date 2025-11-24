@extends('landing.layout.app')

@section('content')

<div class="max-w-xl mx-auto text-center py-16 px-6">

    <h2 class="text-3xl font-bold mb-4 text-gray-800">
        Cek Pesanan Anda
    </h2>

    <p class="text-gray-600 mb-8">
        Masukkan <strong>Kode Unik</strong> yang Anda dapatkan pada invoice.
    </p>

    <form action="{{ route('cekpesanan.check') }}" method="POST">
        @csrf
        <input type="text" 
               name="kode_unik"
               class="w-full p-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Masukkan kode unik Anda..."
               required>

        <button type="submit"
            class="mt-6 w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
            Cek Pesanan
        </button>
    </form>

</div>

@endsection
