@extends('landing.layout.app')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow mt-10">

    <h1 class="text-2xl font-bold mb-4 text-center">Beri Testimoni</h1>

    <p class="text-gray-600 mb-6 text-center">
        Untuk pesanan: <strong>{{ $order->service->name }}</strong><br>
        Atas nama: <strong>{{ $order->name }}</strong>
    </p>

    {{-- FORM TESTIMONI --}}
    <form action="{{ route('testimonial.store', $order->id) }}" method="POST">
        @csrf

        <label class="font-semibold">Rating</label>
        <select name="rating" class="w-full border p-2 rounded mb-4" required>
            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
            <option value="4">⭐⭐⭐⭐ (Puas)</option>
            <option value="3">⭐⭐⭐ (Cukup)</option>
            <option value="2">⭐⭐ (Kurang)</option>
            <option value="1">⭐ (Tidak Puas)</option>
        </select>

        <label class="font-semibold">Testimoni</label>
        <textarea name="message" rows="5"
            class="w-full border p-2 rounded mb-4"
            placeholder="Tulis pengalaman kamu...">{{ old('message') }}</textarea>

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Kirim Testimoni
        </button>

    </form>

</div>

@endsection
