@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto my-10">

    {{-- ============================= --}}
    {{-- HEADER DETAIL PESANAN --}}
    {{-- ============================= --}}
    <div class="glass rounded-xl p-6 mb-8 shadow">

        <h2 class="text-2xl font-bold mb-4">Cek Pesanan Anda</h2>

        @php
            // Tentukan IG dari dua kemungkinan field
            $instagram = $order->instagram_username ?: $order->instagram;
            $instagramFormatted = $instagram ? ltrim($instagram, '@') : null;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-700">

            <p>
                <strong>Nama:</strong> <br>
                {{ $order->name }}
            </p>

            <p>
                <strong>Email:</strong> <br>
                {{ $order->email }}
            </p>

            <p>
                <strong>Kode Unik:</strong> <br>
                {{ $order->kode_unik ?? '-' }}
            </p>

            <p>
                <strong>No WhatsApp:</strong> <br>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->phone) }}"
                class="text-blue-600 hover:underline"
                target="_blank">
                    {{ $order->phone }}
                </a>
            </p>

            <p>
                <strong>Layanan:</strong> <br>
                {{ $order->service->name }}
            </p>

            <p>
                <strong>Instagram:</strong> <br>
                @if ($instagramFormatted)
                    <a href="https://instagram.com/{{ $instagramFormatted }}"
                    target="_blank"
                    class="text-blue-600 hover:underline">
                        {{ '@' . $instagramFormatted }}
                    </a>
                @else
                    -
                @endif
            </p>

            <p>
            <strong>Timeline:</strong> <br>
            @php
                $startDate = $order->created_at->copy();
                $endDate = $order->created_at->copy()->addDays(30); // paket 30 hari dari tanggal mulai
            @endphp

            {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
            </p>


            <p class="md:col-span-2">
                <strong>Catatan Tambahan:</strong> <br>
                {{ $order->notes ?: '-' }}
            </p>

        </div>

    </div>



    {{-- ============================= --}}
    {{-- PROGRESS BAR --}}
    {{-- ============================= --}}
    <div class="glass rounded-xl p-6 mb-8 shadow">
        <h3 class="text-xl font-bold mb-3">Progress Layanan</h3>

        <div class="w-full bg-gray-300 h-4 rounded-full">
            <div class="bg-blue-600 h-4 rounded-full"
                 style="width: {{ $order->progress }}%">
            </div>
        </div>

        <p class="mt-2 font-semibold">{{ $order->progress }}% Selesai</p>
    </div>

    {{-- ============================================================= --}}
    {{--                       TOMBOL TESTIMONI                        --}}
    {{-- ============================================================= --}}
    <div class="text-center mt-10">
        @if($order->progress == 100)
            <a href="{{ route('testimonial.create', $order->id) }}"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded text-lg font-semibold shadow">
                ⭐ Buat Testimoni
            </a>
        @else
            <p class="text-gray-500 italic text-sm">
                Testimoni dapat dibuat setelah progress mencapai <strong>100%</strong>.
            </p>
        @endif
    </div>


    {{-- ============================= --}}
    {{-- JADWAL PENGIRIMAN KONTEN --}}
    {{-- ============================= --}}
    <div class="glass rounded-xl p-6 mb-8 shadow">
        <h3 class="text-xl font-bold mb-4">Jadwal Pengiriman Konten</h3>

        @php
            $timeline = $order->progressItems;

            // Group berdasarkan tanggal schedule
            $grouped = $timeline->groupBy(fn($row) => \Carbon\Carbon::parse($row->scheduled_date)->format('Y-m-d'));
        @endphp

        @foreach($grouped as $date => $items)
            <div class="mb-6 border-b pb-4">

                <p class="text-blue-600 font-semibold mb-2">
                    📅 {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                </p>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="p-2">Jenis</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">File Konten</th>
                            <th class="p-2">Caption</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)

                            @php
                                // Normalisasi type biar Reels/Feed/Story aman walau beda kapital
                                $type = strtolower($item->content_type);

                                // Filter konten berdasarkan type lowercase
                                $contentsOfType = $order->contents
                                    ->filter(fn($c) => strtolower($c->content_type) === $type)
                                    ->sortBy('created_at')
                                    ->values();

                                // Cocokkan dengan progress index (mulai dari 1)
                                $content = $contentsOfType->get($item->content_index - 1);

                                $previewUrl = $content ? route('orders.content.preview', $content->id) : null;
                            @endphp

                            <tr class="border-b">

                                {{-- Jenis --}}
                                <td class="p-2 capitalize">{{ $item->content_type }}</td>

                                {{-- Status --}}
                                <td class="p-2">
                                    @if($item->status == 'Selesai')
                                        <span class="text-green-600 font-semibold">Selesai</span>
                                    @else
                                        <span class="text-yellow-600 font-semibold">Proses</span>
                                    @endif
                                </td>

                                {{-- Preview --}}
                                <td class="p-2">
                                    @if($content)
                                        <a href="{{ $previewUrl }}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                            Preview
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Caption --}}
                                <td class="p-2">
                                    @if($content && $content->caption)
                                        <button
                                            onclick="openCaptionModal(`{!! addslashes($content->caption) !!}`)"
                                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded">
                                            Lihat Caption
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Aksi (Download) --}}
                                <td class="p-2">
                                    @if($content)
                                        <a href="{{ route('orders.content.download', $content->id) }}"
                                           class="bg-blue-600 text-white px-3 py-1 rounded">
                                            Download
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                            </tr>

                        @endforeach
                    </tbody>
                </table>

            </div>
        @endforeach
    </div>

</div>

<!-- =============================== -->
<!-- MODAL CAPTION -->
<!-- =============================== -->
<div id="captionModal"
     class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 relative animate-fadeIn">

        <!-- Tombol Close (X) -->
        <button onclick="closeCaptionModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-xl font-bold">
            &times;
        </button>

        <!-- Judul -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            📝 Caption Konten
        </h2>

        <!-- Isi Caption -->
        <div class="bg-gray-100 p-4 rounded-lg text-gray-700 whitespace-pre-line leading-relaxed shadow-inner"
             id="captionText">
        </div>

        <!-- Tombol -->
        <div class="flex justify-between mt-6">
            <button onclick="copyCaption()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                📋 Copy Caption
            </button>

            <button onclick="closeCaptionModal()"
                class="bg-red-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                ✖ Tutup
            </button>
        </div>

    </div>
</div>

<!-- ANIMASI -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to   { opacity: 1; transform: scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.25s ease-out; }
</style>

<script>
// CAPTION
function openCaptionModal(text) {
    document.getElementById('captionText').innerText = text;
    document.getElementById('captionModal').classList.remove('hidden');
}
function closeCaptionModal() {
    document.getElementById('captionModal').classList.add('hidden');
}
function copyCaption() {
    let text = document.getElementById('captionText').innerText;
    navigator.clipboard.writeText(text);
    alert("Caption berhasil dicopy!");
}
</script>

@endsection
