@extends('admin.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Daftar Testimoni Klien ⭐</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full table-auto">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-4 text-left font-semibold">Nama</th>
                    <th class="p-4 text-left font-semibold">Rating</th>
                    <th class="p-4 text-left font-semibold">Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($testimonials as $testimonial)
                    <tr class="border-b hover:bg-gray-50">

                        {{-- Nama --}}
                        <td class="p-4">{{ $testimonial->name }}</td>

                        {{-- Rating --}}
                        <td class="p-4">
                            @for($i = 0; $i < $testimonial->rating; $i++)
                                <span class="text-yellow-400 text-lg">★</span>
                            @endfor
                        </td>

                        {{-- Status --}}
                        <td class="p-4">
                            @if($testimonial->status == 1)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-sm font-semibold">
                                    Ditampilkan
                                </span>
                            @else
                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm font-semibold">
                                    Disembunyikan
                                </span>
                            @endif
                        </td>

                        

                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

</div>

@endsection
