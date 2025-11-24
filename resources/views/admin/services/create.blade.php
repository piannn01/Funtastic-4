@extends('admin.layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Tambah Layanan Baru</h2>

<form action="{{ route('admin.services.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
    @csrf

    <div class="grid md:grid-cols-2 gap-6">

        {{-- Nama Layanan --}}
        <div>
            <label class="block font-semibold mb-2">Nama Layanan</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded p-2" required>
        </div>

        {{-- Harga --}}
        <div>
            <label class="block font-semibold mb-2">Harga (Rp)</label>
            <input type="number" name="price" class="w-full border-gray-300 rounded p-2" required>
        </div>

        {{-- Feed --}}
        <div>
            <label class="block font-semibold mb-2">Jumlah Feed</label>
            <input type="number" name="feed" class="w-full border-gray-300 rounded p-2" placeholder="cth: 8">
        </div>

        {{-- Stories --}}
        <div>
            <label class="block font-semibold mb-2">Jumlah Stories</label>
            <input type="number" name="stories" class="w-full border-gray-300 rounded p-2" placeholder="cth: 12">
        </div>

        {{-- Video Reels --}}
        <div>
            <label class="block font-semibold mb-2">Jumlah Video Reels</label>
            <input type="number" name="video_reels" class="w-full border-gray-300 rounded p-2" placeholder="cth: 3">
        </div>

        {{-- Durasi --}}
        <div>
            <label class="block font-semibold mb-2">Durasi Layanan</label>
            <input type="text" name="duration" class="w-full border-gray-300 rounded p-2" placeholder="cth: 30 hari">
        </div>

    </div>

    {{-- Deskripsi --}}
    <div class="mt-4">
        <label class="block font-semibold mb-2">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full border-gray-300 rounded p-2"></textarea>
    </div>

    {{-- Status --}}
    <div class="mt-4">
        <label class="block font-semibold mb-2">Status</label>
        <select name="status" class="w-full border-gray-300 rounded p-2">
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
    </div>

    {{-- Buttons --}}
    <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Simpan Layanan
        </button>
        <a href="{{ route('admin.services.index') }}" class="ml-3 text-gray-600 hover:text-gray-800">Batal</a>
    </div>
</form>
@endsection
