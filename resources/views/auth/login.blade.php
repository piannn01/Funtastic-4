<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Funtastic 4</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-blue-600">Login Admin</h2>

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <div class="mb-4">
        <label for="email" class="block mb-1 text-gray-700">Email</label>
        <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required>
      </div>
      <div class="mb-6">
        <label for="password" class="block mb-1 text-gray-700">Password</label>
        <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
      </div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
        Login
      </button>
    </form>
  </div>
</body>
</html>
