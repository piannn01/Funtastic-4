<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Konten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black min-h-screen flex flex-col items-center justify-center p-4">

    {{-- Tombol Kembali --}}
    <div class="absolute top-5 left-5">
        <a href="{{ url('/cek-pesanan/hasil?kode_unik='.$kode_unik) }}"
            class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            ← Kembali
        </a>
    </div>

    {{-- Container Konten --}}
    <div class="max-w-4xl w-full bg-white rounded-xl shadow-lg p-4 flex justify-center">

        {{-- Foto --}}
        @if(in_array($ext, ['jpg','jpeg','png','webp']))
            <img src="{{ asset('storage/'.$file_path) }}"
                 class="max-h-[80vh] w-auto rounded-lg shadow-lg object-contain">
        @endif

        {{-- Video --}}
        @if(in_array($ext, ['mp4','mov','webm']))
            <video controls autoplay
                   class="max-h-[80vh] w-auto rounded-lg shadow-lg object-contain">
                <source src="{{ asset('storage/'.$file_path) }}">
            </video>
        @endif

    </div>

</body>
</html>
