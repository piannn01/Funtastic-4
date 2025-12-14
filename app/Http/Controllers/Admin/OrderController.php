<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderContent;
use App\Models\OrderProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST ORDER + FILTER + SORT + SEARCH + DATE RANGE
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
{
    // ambil parameter dari URL
    $search        = $request->query('search');
    $serviceId     = $request->query('service_id');
    $status        = $request->query('status');
    $paymentStatus = $request->query('payment_status');

    // single date filter (baru)
    $date = $request->query('date');

    // fallback kalau masih ada URL lama from/to
    $from = $request->query('from');
    $to   = $request->query('to');

    $sortBy  = $request->query('sort_by', 'created_at');
    $sortDir = $request->query('sort_dir', 'desc');

    // whitelist sort
    $allowedSortBy = ['created_at', 'name', 'email', 'price', 'status', 'payment_status'];
    if (!in_array($sortBy, $allowedSortBy)) {
        $sortBy = 'created_at';
    }

    $allowedSortDir = ['asc', 'desc'];
    if (!in_array($sortDir, $allowedSortDir)) {
        $sortDir = 'desc';
    }

    $ordersQuery = Order::with('service');

    // SEARCH
    if ($search) {
        $ordersQuery->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('instagram_username', 'like', "%{$search}%")
              ->orWhere('instagram', 'like', "%{$search}%");
        });
    }

    // FILTER by service
    if ($serviceId) {
        $ordersQuery->where('service_id', $serviceId);
    }

    // FILTER by status
    if ($status) {
        $ordersQuery->where('status', $status);
    }

    // FILTER by payment status
    if ($paymentStatus) {
        $ordersQuery->where('payment_status', $paymentStatus);
    }

    // ✅ FILTER SINGLE DATE (priority)
    if ($date) {
        $ordersQuery->whereDate('created_at', Carbon::parse($date)->toDateString());
    }
    // ✅ fallback filter range kalau URL lama masih dipakai
    elseif ($from && $to) {
        $ordersQuery->whereBetween('created_at', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay(),
        ]);
        // biar view baru tetap kebaca tanggalnya
        $date = $from; 
    }

    // SORTING
    $ordersQuery->orderBy($sortBy, $sortDir);

    // pagination
    $orders = $ordersQuery->paginate(10)->appends($request->query());

    // dropdown services
    $services = Service::orderBy('name')->get();

    return view('admin.orders.index', compact(
        'orders',
        'services',
        'search',
        'serviceId',
        'status',
        'paymentStatus',
        'sortBy',
        'sortDir',
        'date' 
    ));
}


    /*
    |--------------------------------------------------------------------------
    | SHOW DETAIL ORDER
    |--------------------------------------------------------------------------
    */
    public function show(Order $order)
    {
        $order->load('service', 'contents', 'progressItems');

        // Timeline jadwal pengiriman konten
        $timeline = $order->progressItems()
            ->orderBy('scheduled_date')
            ->orderBy('content_index')
            ->get();

        return view('admin.orders.show', [
            'order'    => $order,
            'timeline' => $timeline
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT ORDER
    |--------------------------------------------------------------------------
    */
    public function edit(Order $order)
    {
        $services = Service::all();
        return view('admin.orders.edit', compact('order', 'services'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS ORDER
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS ORDER
    |--------------------------------------------------------------------------
    */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE CATATAN PROGRESS
    |--------------------------------------------------------------------------
    */
    public function updateProgress(Request $request, $orderId)
    {
        $request->validate([
            'progress_note' => 'nullable|string'
        ]);

        $order = Order::findOrFail($orderId);
        $order->progress_note = $request->progress_note;
        $order->save();

        $order->refreshProgress();

        return back()->with('success', 'Catatan progress berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD KONTEN (ADMIN → KLIEN)
    |--------------------------------------------------------------------------
    | NOTE:
    | Route baru kamu pakai OrderContentController@store.
    | Tapi method ini tetap aku keep biar tidak break kalau masih dipakai.
    */
    public function uploadContent(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'content_type' => 'required|in:feed,story,reels',
            'file_path'    => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,webm|max:20480',
            'caption'      => 'nullable|string',
        ]);

        // SIMPAN FILE
        $path = $request->file('file_path')->store('order_contents', 'public');

        // SIMPAN KE DB
        OrderContent::create([
            'order_id'     => $orderId,
            'content_type' => $request->content_type,
            'file_path'    => $path,
            'caption'      => $request->caption,
        ]);

        // UPDATE TIMELINE → tandai entry pertama yang belum selesai
        $next = OrderProgress::where('order_id', $orderId)
            ->where('content_type', $request->content_type)
            ->where('status', 'Belum')
            ->orderBy('content_index', 'asc')
            ->first();

        if ($next) {
            $next->status = 'Selesai';
            $next->updated_at = now();
            $next->save();
        }

        // HITUNG ULANG PROGRESS
        $order->refreshProgress();

        return back()->with('success', 'Konten berhasil diupload & progress otomatis diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS untuk kompatibilitas route baru
    |--------------------------------------------------------------------------
    | Jika suatu tempat masih manggil store/delete lewat controller ini,
    | method ini bakal forward ke uploadContent/deleteContent.
    */
    public function store(Request $request, $orderId)
    {
        return $this->uploadContent($request, $orderId);
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS KONTEN
    |--------------------------------------------------------------------------
    */
    public function deleteContent($id)
    {
        $content = OrderContent::findOrFail($id);
        $order   = $content->order;

        if (Storage::disk('public')->exists($content->file_path)) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        // Update progress lagi
        $order->refreshProgress();

        return back()->with('success', 'Konten berhasil dihapus.');
    }

    // alias kompatibilitas untuk route delete baru
    public function delete($id)
    {
        return $this->deleteContent($id);
    }
}
