<nav class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold text-gray-700">{{ $page ?? 'Dashboard' }}</h1>
    <span class="text-gray-500">Halo, {{ Auth::user()->name ?? 'Admin' }}</span>
</nav>
