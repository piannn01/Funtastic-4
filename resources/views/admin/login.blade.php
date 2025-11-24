<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-96 relative">

        {{-- Badge Peringatan --}}
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
            <span class="bg-red-600 text-white px-4 py-1 rounded-full text-xs shadow">
                ⚠️ Akses Khusus Admin
            </span>
        </div>

        {{-- Title --}}
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">
            Login Admin
        </h2>

        {{-- Himbauan --}}
        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 text-sm p-3 rounded mb-5">
            Halaman ini hanya diperuntukkan bagi <strong>Admin</strong> untuk mengelola konten
            dan sistem layanan. Pengguna umum tidak dapat mengakses.
        </div>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            {{-- Email --}}
            <label class="font-medium text-gray-700">Email</label>
            <input type="email" name="email"
                class="w-full border border-gray-300 p-2 rounded-lg mb-4 focus:ring focus:ring-blue-200"
                placeholder="admin@example.com" required>

            {{-- Password --}}
            <label class="font-medium text-gray-700">Password</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 p-2 rounded-lg mb-6 focus:ring focus:ring-blue-200"
                placeholder="********" required>

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold shadow">
                Login
            </button>
        </form>

        <p class="text-center text-gray-400 text-xs mt-5">
            © {{ date('Y') }} Funtastic 4 — Admin Panel
        </p>

    </div>

</body>

</html>
