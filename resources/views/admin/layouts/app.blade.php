<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Funtastic 4') }}</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow mb-6">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-blue-600">Funtastic 4</h1>

            <div class="space-x-6">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="{{ route('admin.services.index') }}" class="hover:underline">Layanan</a>
                <a href="{{ route('admin.orders.index') }}" class="hover:underline">Pesanan</a>
                <a href="{{ route('admin.testimonials.index') }}" class="hover:underline">Testimoni</a>
                <a href="{{ route('admin.reports.finance') }}"
                class="{{ request()->routeIs('admin.reports.finance') ? 'text-blue-600 font-semibold' : '' }}">
                Laporan Keuangan
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="text-red-500 hover:underline">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen">
        @yield('content')
    </div>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-white py-6 mt-10">
        <div class="text-center">
            © {{ date('Y') }} Funtastic 4 - Social Media Management
        </div>
    </footer>

</body>
</html>
