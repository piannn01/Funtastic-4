@extends('admin.layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Pengaturan Website</h2>

<form action="/admin/settings" method="POST" class="bg-white p-6 rounded shadow max-w-lg">
    @csrf @method('PUT')
    <div class="mb-4">
        <label class="block text-gray-700">Nama Website</label>
        <input type="text" name="site_name" class="w-full border p-2 rounded" value="{{ $settings->site_name ?? '' }}" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Deskripsi</label>
        <textarea name="description" class="w-full border p-2 rounded">{{ $settings->description ?? '' }}</textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Email Kontak</label>
        <input type="email" name="contact_email" class="w-full border p-2 rounded" value="{{ $settings->contact_email ?? '' }}">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Nomor Telepon</label>
        <input type="text" name="contact_phone" class="w-full border p-2 rounded" value="{{ $settings->contact_phone ?? '' }}">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Alamat</label>
        <textarea name="address" class="w-full border p-2 rounded">{{ $settings->address ?? '' }}</textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
</form>
@endsection
