<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderContentController extends Controller
{
    /**
     * Upload konten untuk klien
     */
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'content_type' => 'required|in:feed,story,reels',
            'file_path'    => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,webm|max:20480',
            'caption'      => 'nullable|string',
        ]);

        $order = Order::findOrFail($orderId);

        // Upload file
        $path = $request->file('file_path')->store('order_contents', 'public');

        // Simpan ke tabel order_contents
        OrderContent::create([
            'order_id'     => $orderId,
            'content_type' => $request->content_type,
            'file_path'    => $path,
            'caption'      => $request->caption,
        ]);

        // UPDATE progress otomatis
        $this->updateProgressAuto($orderId, $request->content_type);

        return back()->with('success', 'Konten berhasil diupload!');
    }


    /**
     * Hapus konten
     */
    public function delete($id)
    {
        $content = OrderContent::findOrFail($id);

        if (Storage::disk('public')->exists($content->file_path)) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return back()->with('success', 'Konten berhasil dihapus.');
    }


    /**
     * Download file konten
     */
    public function download($id)
    {
        $content = OrderContent::findOrFail($id);

        if (!Storage::disk('public')->exists($content->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($content->file_path);
    }


    /**
     * Update progress otomatis
     */
    private function updateProgressAuto($orderId, $type)
    {
        // cari progress yang status = Belum
        $next = \DB::table('order_progress')
            ->where('order_id', $orderId)
            ->where('content_type', $type)
            ->where('status', 'Belum')
            ->orderBy('content_index')
            ->first();

        if ($next) {
            \DB::table('order_progress')
                ->where('id', $next->id)
                ->update([
                    'status' => 'Selesai',
                    'updated_at' => now(),
                ]);
        }

        // hitung ulang progress
        $order = Order::find($orderId);
        $order->refreshProgress();
    }
}
