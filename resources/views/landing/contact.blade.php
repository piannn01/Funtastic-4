@extends('landing.layout')

@section('content')
<section class="py-16 max-w-4xl mx-auto px-6">
    <h1 class="text-3xl font-bold text-center mb-10">Hubungi Kami</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.send') }}" method="POST" class="bg-white p-8 rounded-xl shadow space-y-4">
        @csrf
        <div>
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="name" class="border w-full p-2 rounded" required>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" class="border w-full p-2 rounded" required>
        </div>

        <div>
            <label class="block mb-1 font-semibold">Pesan</label>
            <textarea name="message" class="border w-full p-2 rounded" rows="5" required></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Kirim Pesan</button>
    </form>
</section>
@endsection
