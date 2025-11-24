@extends('admin.layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4">

    {{-- ========================================================= --}}
    {{--                      HEADER PAGE                         --}}
    {{-- ========================================================= --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Layanan</h1>

        <a href="{{ route('admin.services.create') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition">
            + Tambah Layanan
        </a>
    </div>

    {{-- ========================================================= --}}
    {{--                          TABLE                           --}}
    {{-- ========================================================= --}}
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">

        <table class="w-full table-auto">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="p-4 text-left">#</th>
                    <th class="p-4 text-left">Nama Layanan</th>
                    <th class="p-4 text-left">Harga</th>
                    <th class="p-4 text-center">Feed</th>
                    <th class="p-4 text-center">Stories</th>
                    <th class="p-4 text-center">Video Reels</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($services as $service)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="p-4 text-gray-700">{{ $loop->iteration }}</td>

                    <td class="p-4 font-semibold text-gray-900">
                        {{ $service->name }}
                    </td>

                    <td class="p-4 font-bold text-blue-700">
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    </td>

                    <td class="p-4 text-center">{{ $service->feed }}</td>
                    <td class="p-4 text-center">{{ $service->stories }}</td>
                    <td class="p-4 text-center">{{ $service->video_reels }}</td>

                    <td class="p-4 text-center">
                        @if($service->status == 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                Nonaktif
                            </span>
                        @endif
                    </td>

                    <td class="p-4 text-center space-x-2">

                        {{-- Edit --}}
                        <a href="{{ route('admin.services.edit', $service->id) }}"
                           class="px-4 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded shadow-md transition">
                            Edit
                        </a>

                        {{-- Hapus --}}
                        <form action="{{ route('admin.services.destroy', $service->id) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="px-4 py-1 bg-red-500 hover:bg-red-600 text-white rounded shadow-md transition">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

@endsection
