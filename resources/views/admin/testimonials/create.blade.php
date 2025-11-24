@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">
        {{ isset($testimonial) ? 'Edit Testimoni' : 'Tambah Testimoni' }}
    </h1>

    <form method="POST" enctype="multipart/form-data"
          action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial->id) : route('admin.testimonials.store') }}">
        @csrf
        @if(isset($testimonial)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nama Klien</label>
            <input type="text" name="client_name" class="w-full border p-2 rounded"
                   value="{{ old('client_name', $testimonial->client_name ?? '') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Perusahaan</label>
            <input type="text" name="company" class="w-full border p-2 rounded"
                   value="{{ old('company', $testimonial->company ?? '') }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Pesan</label>
            <textarea name="message" class="w-full border p-2 rounded">{{ old('message', $testimonial->message ?? '') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" class="w-24 border p-2 rounded"
                   value="{{ old('rating', $testimonial->rating ?? 5) }}">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Foto Klien</label>
            <input type="file" name="photo" class="border p-2 rounded w-full">
            @if(isset($testimonial) && $testimonial->photo)
                <img src="{{ asset('storage/' . $testimonial->photo) }}" class="mt-3 w-32 rounded">
            @endif
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Status</label>
            <select name="status" class="border p-2 rounded">
                <option value="active" {{ old('status', $testimonial->status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $testimonial->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection
