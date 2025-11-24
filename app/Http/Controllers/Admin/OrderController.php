<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\OrderContent;
use App\Models\OrderProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST ORDER
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $orders = Order::with('service')->latest()->get();
        return view('admin.orders.index', compact('orders'));
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

    // Kirim ke view
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
        $request->validate(['status' => 'required|string']);

        $order->update(['status' => $request->status]);

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
        $request->validate(['progress_note' => 'nullable|string']);

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

        /*
        |--------------------------------------------------------------------------
        | UPDATE TIMELINE → SET STATUS SELESAI UNTUK ENTRY PERTAMA YANG BELUM SELESAI
        |--------------------------------------------------------------------------
        */
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

        // HITUNG ULANG PROGRESS (%)
        $order->refreshProgress();

        return back()->with('success', 'Konten berhasil diupload & progress otomatis diperbarui.');
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
}
