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
        <select name="rating"
                class="w-full border p-2 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (Sangat Puas)</option>
            <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (Puas)</option>
            <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐ (Cukup)</option>
            <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐ (Kurang)</option>
            <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐ (Tidak Puas)</option>
        </select>

        <label class="font-semibold">Testimoni</label>
        <textarea name="message" rows="5"
                  class="w-full border p-2 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Tulis pengalaman kamu..."
                  required>{{ old('message') }}</textarea>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Kirim Testimoni
        </button>

    </form>

</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Notif sukses --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: @json(session('success')),
        confirmButtonText: 'Oke'
    });
</script>
@endif

{{-- Notif error validasi --}}
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonText: 'Oke'
    });
</script>
@endif

@endsection
