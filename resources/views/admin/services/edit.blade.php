@extends('admin.layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Edit Layanan</h2>

<form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="block font-semibold mb-2">Nama Layanan</label>
            <input type="text" name="name" value="{{ $service->name }}" class="w-full border-gray-300 rounded p-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">Harga (Rp)</label>
            <input type="number" name="price" value="{{ $service->price }}" class="w-full border-gray-300 rounded p-2" required>
        </div>

        <div>
            <label class="block font-semibold mb-2">Feed</label>
            <input type="text" name="feed" value="{{ $service->feed }}" class="w-full border-gray-300 rounded p-2">
        </div>

        <div>
            <label class="block font-semibold mb-2">Stories</label>
            <input type="text" name="stories" value="{{ $service->stories }}" class="w-full border-gray-300 rounded p-2">
        </div>

        <div>
            <label class="block font-semibold mb-2">Video Reels</label>
            <input type="text" name="video_reels" value="{{ $service->video_reels }}" class="w-full border-gray-300 rounded p-2">
        </div>

        <div>
            <label class="block font-semibold mb-2">Durasi</label>
            <input type="text" name="duration" value="{{ $service->duration }}" class="w-full border-gray-300 rounded p-2">
        </div>
    </div>

    <div class="mt-4">
        <label class="block font-semibold mb-2">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full border-gray-300 rounded p-2">{{ $service->description }}</textarea>
    </div>

    <div class="mt-4">
        <label class="block font-semibold mb-2">Status</label>
        <select name="status" class="w-full border-gray-300 rounded p-2">
            <option value="active" {{ $service->status == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ $service->status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>

    <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Update
        </button>
        <a href="{{ route('admin.services.index') }}" class="ml-3 text-gray-600 hover:text-gray-800">Kembali</a>
    </div>
</form>
@endsection
