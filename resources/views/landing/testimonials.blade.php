@extends('landing.layout.app')

@section('content')

<section class="pt-32 pb-20 bg-gray-50">
    <div class="max-w-5xl mx-auto px-6">

        <h1 class="text-4xl font-extrabold text-center mb-10">
            Testimoni Klien Kami
        </h1>

        @forelse ($testimonials as $t)
        <div class="bg-white p-6 rounded-xl shadow mb-6">

            <div class="flex items-center gap-4">
                <div class="w-14 h-14 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xl">
                    {{ strtoupper(substr($t->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="font-bold text-xl">{{ $t->name }}</h2>
                    <p class="text-yellow-500">⭐ {{ $t->rating }}/5</p>
                </div>
            </div>

            <p class="mt-4 text-gray-700 italic">“{{ $t->message }}”</p>
        </div>

        @empty
        <p class="text-center text-gray-500">Belum ada testimoni.</p>
        @endforelse

    </div>
</section>

@endsection
