@extends('landing.layout.app')

@section('content')
<div class="pt-24 px-4">

    <h2 class="text-3xl font-extrabold text-center mb-10">Form Pemesanan</h2>

    {{-- Info Paket --}}
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-xl p-8 mb-10 border">
        <h3 class="text-2xl font-bold">{{ $service->name }}</h3>

        <ul class="text-gray-700 mt-4 space-y-1">
            <li><strong>Feed:</strong> {{ $service->feed ?: '-' }}</li>
            <li><strong>Stories:</strong> {{ $service->stories ?: '-' }}</li>
            <li><strong>Video Reels:</strong> {{ $service->video_reels ?: '-' }}</li>
            <li><strong>Durasi:</strong> {{ $service->duration ?: '-' }}</li>
        </ul>

        <p class="text-3xl font-bold text-blue-600 mt-6">
            Rp {{ number_format($service->price, 0, ',', '.') }}
        </p>
    </div>

    {{-- Form --}}
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-xl p-8 border">
        <form action="{{ route('order.submit', $service->id) }}" method="POST">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label class="font-semibold">Nama Lengkap</label>
                    <input type="text" name="name" required
                        class="w-full border rounded p-3">
                </div>

                <div>
                    <label class="font-semibold">Email</label>
                    <input type="email" name="email" required
                        class="w-full border rounded p-3">
                </div>

               <div>
                    <label class="font-semibold">Nomor WhatsApp</label>
                    <input type="text"
                        name="phone"
                        required
                        placeholder="Gunakan format +62 (contoh: +628123456789)"
                        class="w-full border rounded p-3">
                </div>


                {{-- 🔥 FIELD BARU: INSTAGRAM USERNAME --}}
                <div>
                    <label class="font-semibold">Username Instagram</label>
                    <input type="text"
                    name="instagram"
                    required
                    placeholder="@namapengguna"
                    class="w-full border rounded p-3">
                </div>

                <div class="md:col-span-2">
                    <label class="font-semibold">Catatan (Opsional)</label>
                    <textarea name="notes" rows="4"
                        class="w-full border rounded p-3"
                        placeholder="Tambahkan catatan tambahan jika diperlukan"></textarea>
                </div>

            </div>

            <button class="mt-8 w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                Lanjutkan Pembayaran
            </button>

        </form>
    </div>

</div>
@endsection
