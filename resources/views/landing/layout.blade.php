<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funtastic 4 | Jasa Pengelola Media Sosial</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- Navbar --}}
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
            <a href="{{ route('index') }}" class="text-2xl font-bold text-blue-600">Funtastic 4</a>
            <div class="space-x-6">
                <a href="{{ route('services') }}" class="hover:text-blue-600">Layanan</a>
                <a href="{{ route('testimonials') }}" class="hover:text-blue-600">Testimoni</a>
                <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">Hubungi Kami</a>
            </div>
        </div>
    </nav>

    {{-- Konten --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-blue-600 text-white text-center py-6 mt-12">
        <p>&copy; {{ date('Y') }} Funtastic 4. All rights reserved.</p>
    </footer>

</body>
</html>
