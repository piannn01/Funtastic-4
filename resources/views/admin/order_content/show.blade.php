@extends('admin.layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Konten untuk: {{ $order->name }}</h2>

<a href="{{ route('admin.orderContents.create', $order->id) }}"
   class="bg-blue-600 text-white px-4 py-2 rounded">Upload Konten Baru</a>

<div class="mt-6">
    <h3 class="text-xl font-bold">Konten Terkirim:</h3>

    @forelse($contents as $c)
        <div class="p-4 bg-white shadow rounded mt-4">
            <p><strong>Tipe:</strong> {{ ucfirst($c->type) }}</p>
            <p><strong>Deskripsi:</strong> {{ $c->description }}</p>

            @if(str_contains($c->file_path, '.mp4'))
                <video src="{{ asset('storage/'.$c->file_path) }}" controls class="w-48 mt-3"></video>
            @else
                <img src="{{ asset('storage/'.$c->file_path) }}" class="w-48 mt-3 rounded">
            @endif
        </div>
    @empty
        <p class="text-gray-500 mt-4">Belum ada konten yang dikirim.</p>
    @endforelse
</div>

@endsection
