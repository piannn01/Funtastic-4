<nav class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
  <div class="container mx-auto flex justify-between items-center px-4 py-3">
    {{-- Logo --}}
    <a href="{{ route('index') }}" class="text-2xl font-bold text-blue-600">Funtastic 4</a>

    {{-- Menu --}}
    <div class="flex items-center space-x-6">
      <a href="{{ route('services') }}" class="hover:text-blue-600">Layanan</a>
      <a href="{{ route('testimonials') }}" class="hover:text-blue-600">Testimoni</a>

      {{-- Tombol Hubungi Kami --}}
      <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700">
        Hubungi Kami
      </a>

      {{-- Tombol Login Admin --}}
      <a href="{{ route('admin.login') }}" class="border border-blue-600 text-blue-600 px-4 py-2 rounded-full hover:bg-blue-600 hover:text-white">
        Login Admin
      </a>
    </div>
  </div>
</nav>
