<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? 'Funtastic 4 - Social Media Management' }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

    <!-- Swiper Slider -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Floating WA with small animation */
        .float-wa {
            transition: 0.3s ease;
        }
        .float-wa:hover {
            transform: scale(1.08);
        }

        /* Floating animation hero image */
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- NAVBAR PREMIUM --}}
    <nav class="fixed top-0 left-0 w-full bg-white shadow-md py-4 z-50">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

            <a href="/" class="text-2xl font-extrabold text-blue-600 tracking-tight">
                Funtastic 4
            </a>

            <div class="hidden md:flex space-x-8 font-semibold text-gray-700">
                <a href="/" class="hover:text-blue-600">Beranda</a>
                <a href="/services" class="hover:text-blue-600">Layanan</a>
                <a href="/testimonials" class="hover:text-blue-600">Testimoni</a>
                <a href="{{ route('cekpesanan') }}" class="hover:text-blue-600">Cek Pesanan</a>
                <a href="/contact" class="hover:text-blue-600">Kontak</a>
                <a href="/admin/login" class="hover:text-blue-600">Login</a>
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <main class="pt-20 min-h-screen">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('landing.layout.footer')


    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ $setting->whatsapp }}"
   class="float-wa fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-xl text-2xl z-50">
    <i class="fab fa-whatsapp"></i>
    </a>


    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- Swiper Init --}}
    <script>
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 2500 },
            pagination: { el: ".swiper-pagination" },
        });
    </script>

</body>
</html>
