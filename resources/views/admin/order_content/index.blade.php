@extends('admin.layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Daftar Pesanan Klien</h2>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3">Nama</th>
            <th class="p-3">Email</th>
            <th class="p-3">Paket</th>
            <th class="p-3">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($orders as $o)
        <tr class="border-t">
            <td class="p-3">{{ $o->name }}</td>
            <td class="p-3">{{ $o->email }}</td>
            <td class="p-3">{{ $o->service->name }}</td>
            <td class="p-3">
                <a href="{{ route('admin.orderContents.show', $o->id) }}"
                   class="text-blue-600 font-semibold">Kelola Konten</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
