@extends('admin.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- ========================================================= --}}
    {{--                INFORMASI PEMESAN                          --}}
    {{-- ========================================================= --}}
<div class="bg-white shadow rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-bold mb-4">Informasi Pemesan</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <p><strong>Nama:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>

        <p><strong>No WhatsApp:</strong> {{ $order->phone }}</p>
        <p>
            <strong>Instagram:</strong>
            {{ $order->instagram ?? $order->instagram_username ?? '-' }}
        </p>

        <p><strong>Layanan:</strong> {{ $order->service->name }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($order->price,0,',','.') }}</p>

        <p class="col-span-2">
            <strong>Status Pembayaran:</strong>
            @if($order->payment_status === 'paid')
                <span class="text-green-600 font-semibold">Sudah Dibayar</span>
            @else
                <span class="text-red-600 font-semibold">Belum Dibayar</span>
            @endif
        </p>

        {{-- CATATAN KLIEN --}}
        <div class="col-span-2 mt-2">
            <strong>Catatan Klien:</strong>
            @if(!empty($order->notes))
                <div class="mt-2 bg-gray-50 border rounded-lg p-3 text-gray-700 whitespace-pre-line">
                    {{ $order->notes }}
                </div>
            @else
                <p class="text-gray-500 italic mt-1">Tidak ada catatan dari klien.</p>
            @endif
        </div>

    </div>
</div>



    {{-- ========================================================= --}}
    {{--                UPLOAD KONTEN BARU                         --}}
    {{-- ========================================================= --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Upload Konten Baru</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.orders.content.store', $order->id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Jenis Konten --}}
                <div>
                    <label class="font-semibold">Jenis Konten</label>
                    <select name="content_type" class="w-full border rounded px-3 py-2 mt-1" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="feed">Feed</option>
                        <option value="story">Story</option>
                        <option value="reels">Reels</option>
                    </select>
                </div>

                {{-- File --}}
                <div>
                    <label class="font-semibold">File Konten</label>
                    <input type="file"
                           name="file_path"
                           class="w-full border rounded px-3 py-2 mt-1"
                           accept="image/*,video/*"
                           required>
                </div>
            </div>

            {{-- Caption --}}
            <div class="mt-4">
                <label class="font-semibold">Caption (Opsional)</label>
                <textarea name="caption" rows="3"
                          class="w-full border rounded px-3 py-2 mt-1"></textarea>
            </div>

            <button type="submit"
                    class="mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                Upload Konten
            </button>
        </form>
    </div>


    {{-- ========================================================= --}}
    {{--                     PROGRESS BAR                           --}}
    {{-- ========================================================= --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Progress Timeline</h2>

        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-green-600 h-4 rounded-full transition-all duration-500"
                 style="width: {{ $order->progress }}%"></div>
        </div>

        <p class="mt-2 text-gray-700 font-semibold">
            {{ $order->progress }}% Selesai
        </p>
    </div>


    {{-- ========================================================= --}}
    {{--                  TABEL JADWAL KONTEN                      --}}
    {{-- ========================================================= --}}
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Jadwal Konten</h2>

        @php
            $timelineGrouped = $timeline->groupBy(fn($item) => \Carbon\Carbon::parse($item->scheduled_date)->format('Y-m-d'));
        @endphp

        @if($timelineGrouped->count() == 0)
            <p class="text-gray-500">Belum ada jadwal konten.</p>
        @else

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Feed</th>
                        <th class="p-3 text-left">Story</th>
                        <th class="p-3 text-left">Reels</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">WA</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($timelineGrouped as $date => $row)

                    @php
                        $feed  = $row->firstWhere('content_type', 'feed');
                        $story = $row->firstWhere('content_type', 'story');
                        $reels = $row->firstWhere('content_type', 'reels');

                        $done  = $row->where('status', 'Selesai')->count();
                        $total = $row->count();

                        // ✅ tampilkan tombol WA hanya jika ada minimal 1 konten yang Selesai
                        $hasCompletedContent = $done > 0;

                        // WA config (tetap ada, tapi dipakai hanya saat tombol tampil)
                        $waNumber = preg_replace('/[^0-9]/', '', $order->phone);
                        $cekLink  = url('/cek-pesanan/hasil?kode_unik='.$order->kode_unik);

                        $waMessage =
                            "Halo+".urlencode($order->name)."%0A".
                            "Progress+pemesanan+Anda+telah+bertambah.%0A".
                            "Silakan+cek+jadwal+dan+download+konten+melalui+link+berikut:%0A".
                            $cekLink;
                    @endphp

                    <tr class="border-b">
                        <td class="p-3">{{ $date }}</td>

                        <td class="p-3">
                            {{ $feed ? 'Feed #' . $feed->content_index : '-' }}
                        </td>

                        <td class="p-3">
                            {{ $story ? 'Story #' . $story->content_index : '-' }}
                        </td>

                        <td class="p-3">
                            {{ $reels ? 'Reels #' . $reels->content_index : '-' }}
                        </td>

                        <td class="p-3">
                            @if($done === $total)
                                <span class="text-green-600 font-semibold">{{ $done }}/{{ $total }} Selesai</span>
                            @else
                                <span class="text-red-600 font-semibold">{{ $done }}/{{ $total }} Selesai</span>
                            @endif
                        </td>

                        <td class="p-3">
                            @if($hasCompletedContent)
                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                                   target="_blank"
                                   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Send WA
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>

        @endif
    </div>

</div>

@endsection
