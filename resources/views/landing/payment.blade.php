@extends('landing.layout.app')

@section('content')

<div class="max-w-3xl mx-auto py-16 px-6">

    <h2 class="text-3xl font-bold mb-6 text-center text-blue-600">
        Pembayaran Pesanan
    </h2>

    <div class="bg-white p-6 rounded shadow-md">

        <h3 class="text-xl font-semibold mb-3">Detail Pesanan</h3>

        <p><strong>Nama:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Nomor WhatsApp:</strong> {{ $order->phone }}</p>
        <p><strong>Instagram:</strong> {{ $order->instagram ?? '-' }}</p>

        <p class="mt-4 text-lg">
            <strong>Total Pembayaran:</strong>
            <span class="text-green-600 font-bold">
                Rp {{ number_format($order->price, 0, ',', '.') }}
            </span>
        </p>

        {{-- Tombol Pembayaran --}}
        <div class="mt-8 text-center">
            <button id="pay-button"
                class="bg-blue-600 text-white px-6 py-3 rounded shadow hover:bg-blue-700 transition">
                Lanjutkan Pembayaran
            </button>
        </div>

        <p class="mt-5 text-gray-600 text-sm text-center">
            Anda akan diarahkan ke halaman pembayaran Midtrans.
        </p>

    </div>

</div>

{{-- MIDTRANS SNAP JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').onclick = function (e) {
    e.preventDefault();

    snap.pay('{{ $snapToken }}', {

        onSuccess: function (result) {
            // Mark status as PAID immediately
            fetch("/payment/success/update-status/{{ $order->id }}")
                .then(() => {
                    window.location.href = "/invoice/{{ $order->invoice_code }}";
                });
        },

        onPending: function (result) {
            // Tetap arahkan ke invoice (status pending)
            window.location.href = "/invoice/{{ $order->invoice_code }}";
        },

        onError: function (result) {
            alert("Pembayaran gagal. Silakan coba lagi.");
        },

        onClose: function () {
            alert("Anda menutup pembayaran sebelum selesai.");
        }

    });
};
</script>

@endsection
