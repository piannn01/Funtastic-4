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
<<<<<<< HEAD
                <strong>Tanggal Order:</strong> <br>
                {{ $order->created_at->format('d M Y') }}
            </p>

=======
            <strong>Timeline:</strong> <br>
            @php
                $startDate = $order->created_at->copy();
                $endDate = $order->created_at->copy()->addDays(30); // paket 30 hari dari tanggal mulai
            @endphp

            {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
            </p>


>>>>>>> ad2b375 (update)
            <p class="md:col-span-2">
                <strong>Catatan Tambahan:</strong> <br>
                {{ $order->notes ?: '-' }}
            </p>

        </div>

    </div>



<<<<<<< HEAD
=======

>>>>>>> ad2b375 (update)
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
<<<<<<< HEAD

    <div class="text-center mt-10">

=======
    <div class="text-center mt-10">
>>>>>>> ad2b375 (update)
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
<<<<<<< HEAD

=======
>>>>>>> ad2b375 (update)
    </div>


    {{-- ============================= --}}
    {{-- JADWAL PENGIRIMAN KONTEN --}}
    {{-- ============================= --}}
    <div class="glass rounded-xl p-6 mb-8 shadow">
        <h3 class="text-xl font-bold mb-4">Jadwal Pengiriman Konten</h3>

        @php
            $timeline = $order->progressItems;
<<<<<<< HEAD
=======

            // Group berdasarkan tanggal schedule
>>>>>>> ad2b375 (update)
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

<<<<<<< HEAD
                            {{-- Cari konten sesuai jenis + index --}}
                            @php
                                $content = $order->contents
                                    ->where('content_type', $item->content_type)
                                    ->values()
                                    ->get($item->content_index - 1);

                                $url  = $content ? asset('storage/' . $content->file_path) : null;
                                $ext  = $content ? strtolower(pathinfo($content->file_path, PATHINFO_EXTENSION)) : null;
=======
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
>>>>>>> ad2b375 (update)
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
<<<<<<< HEAD

                                    @php
                                        // Ambil file konten yang cocok dengan jenis konten
                                        $file = $order->contents
                                            ->where('content_type', $item->content_type)
                                            ->first();

                                        $previewUrl = $file ? route('orders.content.preview', $file->id) : null;
                                    @endphp

                                    @if($file)
                                        <a href="{{ $previewUrl }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
=======
                                    @if($content)
                                        <a href="{{ $previewUrl }}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
>>>>>>> ad2b375 (update)
                                            Preview
                                        </a>
                                    @else
                                        -
                                    @endif
<<<<<<< HEAD

                                </td>



                                {{-- Caption --}}
                                <td class="p-2">
                                    @if($content && $content->caption)
                                        <button 
=======
                                </td>

                                {{-- Caption --}}
                                <td class="p-2">
                                    @if($content && $content->caption)
                                        <button
>>>>>>> ad2b375 (update)
                                            onclick="openCaptionModal(`{!! addslashes($content->caption) !!}`)"
                                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded">
                                            Lihat Caption
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>

<<<<<<< HEAD
                                {{-- Aksi --}}
=======
                                {{-- Aksi (Download) --}}
>>>>>>> ad2b375 (update)
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
<<<<<<< HEAD
<!-- MODAL CAPTION (Versi Premium) -->
=======
<!-- MODAL CAPTION -->
>>>>>>> ad2b375 (update)
<!-- =============================== -->
<div id="captionModal"
     class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 relative animate-fadeIn">

        <!-- Tombol Close (X) -->
<<<<<<< HEAD
        <button onclick="closeCaptionModal()" 
=======
        <button onclick="closeCaptionModal()"
>>>>>>> ad2b375 (update)
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
<<<<<<< HEAD
            <!-- Caption akan muncul di sini -->
=======
>>>>>>> ad2b375 (update)
        </div>

        <!-- Tombol -->
        <div class="flex justify-between mt-6">
<<<<<<< HEAD

            <!-- Tombol Copy -->
=======
>>>>>>> ad2b375 (update)
            <button onclick="copyCaption()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                📋 Copy Caption
            </button>

<<<<<<< HEAD
            <!-- Tombol Tutup -->
=======
>>>>>>> ad2b375 (update)
            <button onclick="closeCaptionModal()"
                class="bg-red-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                ✖ Tutup
            </button>
<<<<<<< HEAD

=======
>>>>>>> ad2b375 (update)
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
<<<<<<< HEAD
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

function goBackPage() {
    window.history.back();
}
</script>


{{-- ============================= --}}
{{-- MODAL PREVIEW --}}
{{-- ============================= --}}
<div id="previewModal"
     class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">

    <div class="bg-white p-4 rounded shadow max-w-2xl w-full">
        <div id="previewContainer"></div>

        <button onclick="closePreviewModal()" 
                class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">
            Kembali
        </button>
    </div>
</div>

<script>
=======
>>>>>>> ad2b375 (update)
// CAPTION
function openCaptionModal(text) {
    document.getElementById('captionText').innerText = text;
    document.getElementById('captionModal').classList.remove('hidden');
}
function closeCaptionModal() {
    document.getElementById('captionModal').classList.add('hidden');
}
function copyCaption() {
<<<<<<< HEAD
    navigator.clipboard.writeText(document.getElementById('captionText').innerText);
    alert("Caption berhasil disalin!");
}

// PREVIEW
function openPreviewModal(url, type) {
    let container = document.getElementById('previewContainer');
    container.innerHTML = '';

    if (type === 'image') {
        container.innerHTML = `<img src="${url}" class="w-full rounded">`;
    } else {
        container.innerHTML = `
            <video controls autoplay class="w-full rounded">
                <source src="${url}">
            </video>
        `;
    }

    document.getElementById('previewModal').classList.remove('hidden');
}
function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
=======
    let text = document.getElementById('captionText').innerText;
    navigator.clipboard.writeText(text);
    alert("Caption berhasil dicopy!");
>>>>>>> ad2b375 (update)
}
</script>

@endsection
