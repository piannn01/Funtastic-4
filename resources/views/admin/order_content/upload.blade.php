@extends('admin.layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Upload Konten untuk {{ $order->name }}</h2>

<form action="{{ route('admin.orderContents.store', $order->id) }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white p-6 shadow rounded w-full md:w-1/2">

    @csrf

    <label class="block font-semibold">Jenis Konten</label>
    <select name="type" required class="w-full border p-2 rounded">
        <option value="feed">Feed</option>
        <option value="story">Story</option>
        <option value="reels">Reels</option>
    </select>

    <label class="block font-semibold mt-4">Deskripsi</label>
    <textarea name="description" rows="3" class="w-full border p-2 rounded" required></textarea>

    <label class="block font-semibold mt-4">Upload File</label>
    <input type="file" name="file" class="w-full" required>

    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        Kirim ke Klien
    </button>

</form>

@endsection
