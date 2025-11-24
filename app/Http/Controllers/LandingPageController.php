<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Order;
use App\Models\Setting;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
class LandingPageController extends Controller

{
    /* ==========================================
     | HOME PAGE
     ========================================== */
    public function index()
    {
        return view('landing.index', [
            'services'     => Service::where('status', 'active')->take(6)->get(),
            'testimonials' => Testimonial::where('status', true)->latest()->get(),
            'setting'      => Setting::first()
        ]);
    }

    public function about()
    {
        return view('landing.about', ['setting' => Setting::first()]);
    }

    public function services()
    {
        return view('landing.services', [
            'services' => Service::where('status', 'active')->orderBy('price')->get(),
            'setting'  => Setting::first()
        ]);
    }


    /* ==========================================
     | ORDER FORM
     ========================================== */
    public function orderForm($serviceId)
    {
        return view('landing.order', [
            'service' => Service::findOrFail($serviceId),
            'setting' => Setting::first()
        ]);
    }


    /* ==========================================
     | ORDER SUBMIT + GENERATE TIMELINE OTOMATIS
     ========================================== */
    public function orderSubmit(Request $request, $serviceId)
{
    $service = Service::findOrFail($serviceId);

    $request->validate([
        'name'      => 'required',
        'email'     => 'required|email',
        'phone'     => 'required',
        'instagram' => 'nullable|string',
        'notes'     => 'nullable|string',
    ]);

    // Hitung harga dari layanan
    $calculated_price = $service->price;

    // Buat kode invoice unik
    $invoice_code = strtoupper(Str::random(10));
    $kode_unik = strtoupper(Str::random(8));

    // Simpan order
    $order = Order::create([
    'service_id'   => $service->id,
    'name'         => $request->name,
    'email'        => $request->email,
    'phone'        => $request->phone,
    'instagram'    => $request->instagram,
    'notes'        => $request->notes,
    'price'        => $calculated_price,
    'invoice_code' => $invoice_code,
    'kode_unik' => $kode_unik,
    'redirect_url' => url('/invoice/' . $invoice_code),
    ]);


    // Generate progress otomatis
    $order->load('service');
    $order->generateDefaultProgress();

    return redirect()->route('payment.page', $order->id);
}



    /* ==========================================
     | PAYMENT PAGE (Token tidak berubah)
     ========================================== */
    public function paymentPage($orderId)
    {
        $order = Order::findOrFail($orderId);

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // gunakan token lama jika ada
        if (!$order->midtrans_token) {

            $newOrderId = "ORDER-" . $order->id . "-" . time();
            $order->midtrans_order_id = $newOrderId;
            $order->save();

            $payload = [
                'transaction_details' => [
                    'order_id'     => $newOrderId,
                    'gross_amount' => (int) $order->price,
                ],
                'customer_details' => [
                    'first_name' => $order->name,
                    'email'      => $order->email,
                    'phone'      => $order->phone,
                ]
            ];

            $snapToken = Snap::getSnapToken($payload);

            $order->update([
                'midtrans_token' => $snapToken
            ]);
        } else {
            $snapToken = $order->midtrans_token;
        }

        return view('landing.payment', compact('order', 'snapToken'));
    }


    /* ==========================================
     | MIDTRANS CALLBACK
     ========================================== */
    public function midtransCallback(Request $request)
{
    $json = json_decode($request->getContent(), true);
    $serverKey = config('midtrans.server_key');

    $signature = hash(
        "sha512",
        $json['order_id'] .
        $json['status_code'] .
        $json['gross_amount'] .
        $serverKey
    );

    if ($signature !== $json['signature_key']) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    $order = Order::where('midtrans_order_id', $json['order_id'])->first();
    if (!$order) return;

    $status = $json['transaction_status'];

    if (in_array($status, ['settlement', 'capture'])) {
        $order->payment_status = "paid";
    } elseif ($status === "pending") {
        $order->payment_status = "pending";
    } else {
        $order->payment_status = "failed";
    }

    $order->save();

    // ❗ CALLBACK TIDAK BOLEH REDIRECT
    return response()->json(['status' => 'OK']);
}





    /* ==========================================
     | CEK PESANAN
     ========================================== */
    public function cekPesanan()
    {
        return view('landing.cek-pesanan');
    }

    public function cekPesananHasil(Request $request)
    {
    $request->validate(['kode_unik' => 'required|string']);

    $order = Order::where('kode_unik', $request->kode_unik)
        ->with(['contents', 'service', 'progressItems'])
        ->firstOrFail();

    return view('landing.cek-pesanan-hasil', compact('order'));
    }


    /* ==========================================INVOCIE========================================== */
    public function invoice($invoice_code)
    {
    $order = Order::where('invoice_code', $invoice_code)->firstOrFail();
    return view('invoice.show', compact('order'));
    }

    public function markAsPaid(Order $order)
    {
        $order->payment_status = 'paid';
        $order->save();

        return response()->json(['message' => 'Status updated to PAID']);
    }


    /* ==========================================TESTIMONI========================================== */
    public function createTestimonial($orderId)
    {
        return view('landing.testimonial.create', [
            'order' => Order::findOrFail($orderId)
        ]);
        if ($order->progress_percent < 100) {
        return redirect()->route('cekpesanan')
        ->with('error', 'Anda hanya dapat memberikan testimoni setelah layanan selesai 100%.');
    }

    }

    public function storeTestimonial(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'message' => 'required|min:5',
        ]);

        Testimonial::create([
            'order_id' => $order->id,
            'name'     => $order->name,
            'email'    => $order->email,
            'rating'   => $request->rating,
            'message'  => $request->message,
            'status'   => true,
        ]);

        return redirect()->route('cekpesanan')
            ->with('success', 'Terima kasih! Testimoni berhasil dikirim.');
    }


    public function testimonials()
    {
        return view('landing.testimonials', [
            'testimonials' => Testimonial::where('status', true)->latest()->get(),
            'setting'      => Setting::first()
        ]);
    }
}
