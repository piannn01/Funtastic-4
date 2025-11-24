@extends('landing.layout.app')

@section('content')

{{-- HERO SECTION PREMIUM --}}
<section class="pt-32 pb-24 bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 text-white">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

        <div data-aos="fade-right">
            <h1 class="text-5xl md:text-6xl font-extrabold leading-tight drop-shadow-lg">
                Tingkatkan <span class="text-red-600">Brand Bisnis</span> Anda Dengan
                <span class="underline decoration-red-700">Strategi Konten Instagram Profesional</span>
            </h1>

            <p class="mt-6 text-lg text-blue-100">
                Kelola konten, tingkatkan engagement, dan perluas jangkauan bisnis Anda dengan layanan
                social media management dari tim ahli kami.
            </p>

            <div class="mt-10 flex gap-4">
                <a href="{{ route('services') }}"
                    class="px-7 py-3 bg-red-500 text-white hover:bg-white hover:text-red-700 font-bold rounded-xl shadow-lg transition">
                    Lihat Paket Layanan
                </a>

                <a href="{{ route('contact') }}"
                    class="px-7 py-3 border border-white text-white font-semibold rounded-xl hover:bg-white hover:text-blue-600 transition">
                    Konsultasi Gratis
                </a>
            </div>
        </div>

        <div data-aos="fade-left">
            <img src="/assets/Funtastic4.png" class="drop-shadow-2xl animate-float">
        </div>

    </div>
</section>


{{-- SECTION DIVIDER --}}
<div class="wave-divider -mt-1">
    <img src="/assets/wave.svg" class="w-full">
</div>



{{-- LAYANAN PREMIUM --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">

        <h2 data-aos="fade-up" class="text-4xl font-extrabold text-center mb-16">
            Paket Layanan Premium
        </h2>

        <div class="grid md:grid-cols-3 gap-10">

            @foreach ($services as $s)
            <div data-aos="zoom-in" data-aos-delay="100"
                class="bg-white shadow-xl rounded-2xl p-10 hover:-translate-y-2 transition transform border-t-4 border-blue-600">

                <h3 class="text-2xl font-bold text-blue-700">{{ $s->name }}</h3>
                <p class="text-gray-600 mt-3">{{ $s->description }}</p>

                <ul class="mt-5 space-y-2 text-gray-800">
                    <li>📌 Feed: <strong>{{ $s->feed ?? '-' }}</strong></li>
                    <li>📌 Stories: <strong>{{ $s->stories ?? '-' }}</strong></li>
                    <li>📌 Reels: <strong>{{ $s->video_reels ?? '-' }}</strong></li>
                    <li>📌 Durasi: <strong>{{ $s->duration ?? '-' }}</strong></li>
                </ul>

                <p class="text-4xl mt-6 font-extrabold text-blue-600">
                    Rp {{ number_format($s->price, 0, ',', '.') }}
                </p>

                <a href="{{ route('order.form', $s->id) }}"
                    class="mt-8 block text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition">
                    Pesan Sekarang
                </a>

            </div>
            @endforeach

        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('services') }}" class="text-blue-600 font-semibold hover:underline">
                Lihat Semua Paket →
            </a>
        </div>

    </div>
</section>



{{-- TESTIMONIAL SECTION --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">

        <h2 data-aos="fade-up" class="text-4xl font-extrabold text-center mb-12">
            Apa Kata Klien Kami?
        </h2>

        @if($testimonials->count() == 0)
            <p class="text-center text-gray-600">Belum ada testimoni.</p>
        @else

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">

                @foreach ($testimonials as $t)
                <div class="swiper-slide">
                    <div class="bg-white shadow-lg border rounded-2xl p-10 mx-6">

                        {{-- Rating --}}
                        <div class="flex justify-center mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="@if($i <= $t->rating) text-yellow-400 @else text-gray-300 @endif text-2xl">
                                    ★
                                </span>
                            @endfor
                        </div>

                        {{-- Message --}}
                        <p class="italic text-gray-700 text-center">
                            “{{ $t->message }}”
                        </p>

                        {{-- Name --}}
                        <h4 class="font-bold text-lg text-center mt-6">
                            {{ $t->name }}
                        </h4>

                    </div>
                </div>
                @endforeach

            </div>

            <div class="swiper-pagination mt-6"></div>
        </div>

        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('testimonials') }}" class="text-blue-600 font-semibold hover:underline">
                Lihat Semua Testimoni →
            </a>
        </div>

    </div>
</section>




{{-- CTA SECTION PREMIUM --}}
<section class="py-24 text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
    <h2 class="text-4xl md:text-5xl font-extrabold">
        Siap Naik Level Bersama Kami?
    </h2>

    <p class="mt-4 text-xl text-blue-100">
        Konsultasikan kebutuhan social media management Anda sekarang!
    </p>

    <a href="{{ route('contact') }}"
       class="mt-10 inline-block bg-white text-blue-700 font-bold px-12 py-4 rounded-xl shadow-lg hover:bg-blue-100 transition">
        Konsultasi Sekarang →
    </a>
</section>

@endsection
