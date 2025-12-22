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
        <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col md:flex-row md:justify-between md:items-center gap-3">

            {{-- Brand --}}
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-blue-600">Funtastic 4</h1>
            </div>

            {{-- Menu --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm md:text-base">

                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-semibold underline' : 'hover:underline' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.services.index') }}"
                   class="{{ request()->routeIs('admin.services.*') ? 'text-blue-600 font-semibold underline' : 'hover:underline' }}">
                    Layanan
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="{{ request()->routeIs('admin.orders.*') ? 'text-blue-600 font-semibold underline' : 'hover:underline' }}">
                    Pesanan
                </a>

                <a href="{{ route('admin.testimonials.index') }}"
                   class="{{ request()->routeIs('admin.testimonials.*') ? 'text-blue-600 font-semibold underline' : 'hover:underline' }}">
                    Testimoni
                </a>

                <a href="{{ route('admin.reports.finance') }}"
                   class="{{ request()->routeIs('admin.reports.finance') ? 'text-blue-600 font-semibold underline' : 'hover:underline' }}">
                    Laporan Keuangan
                </a>

                {{-- ✅ TAMBAHAN: Laporan Keseluruhan (Owner Report) --}}
                <a href="{{ route('admin.reports.summary') }}"
                   class="{{ request()->routeIs('admin.reports.summary') ? 'text-blue-600 font-semibold underline' : 'hover:underline' }}">
                    Laporan Keseluruhan
                </a>

                {{-- Logout --}}
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline">
                        Logout
                    </button>
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
        <div class="text-center text-sm">
            © {{ date('Y') }} Funtastic 4 - Social Media Management
        </div>
    </footer>

</body>
</html>
