<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funtastic 4</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased text-gray-800">

    {{-- Navbar --}}
    @include('landing.navbar')

    {{-- Konten Halaman --}}
    <main class="pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-100 text-center py-6 mt-20">
        <p class="text-gray-600">© 2025 Funtastic 4. All rights reserved.</p>
    </footer>

</body>
</html>
