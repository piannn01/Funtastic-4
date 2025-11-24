<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderContent;
use App\Models\OrderProgress;
use Illuminate\Support\Facades\Storage;

class OrderTrackController extends Controller
{
    public function form()
    {
        return view('landing.cek-pesanan');
    }


    /* ============================================================
     | CEK PESANAN (CARI KODE)
     ============================================================ */
    public function check(Request $request)
    {
        $request->validate([
            'kode_unik' => 'required|string'
        ]);

        $order = Order::where('kode_unik', $request->kode_unik)->first();

        if (!$order) {
            return back()->with('error', 'Kode unik tidak ditemukan.');
        }

        return redirect()->route('cekpesanan.hasil', ['kode_unik' => $order->kode_unik]);
    }


    /* ============================================================
     | HALAMAN HASIL CEK PESANAN
     ============================================================ */
    public function hasil(Request $request)
    {
        $kode = $request->kode_unik;

        $order = Order::where('kode_unik', $kode)
            ->with(['contents', 'service', 'progressItems'])
            ->first();

        if (!$order) {
            return view('landing.cek-pesanan-hasil', [
                'not_found' => true,
            ]);
        }

        // Ambil timeline dari tabel order_progress
        $timeline = $order->progressItems()
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // Konten yang sudah dikirim admin (file yang diupload)
        $groupedContents = $order->contents
            ->groupBy(fn ($item) => date('Y-m-d', strtotime($item->created_at)))
            ->sortKeysDesc();

        return view('landing.cek-pesanan-hasil', [
            'order'           => $order,
            'timeline'        => $timeline,
            'groupedContents' => $groupedContents,
        ]);
    }

    public function preview($id)
    {
    $content = OrderContent::findOrFail($id);

    $ext = strtolower(pathinfo($content->file_path, PATHINFO_EXTENSION));

    return view('landing.preview', [
        'file_path' => $content->file_path,
        'ext'       => $ext,
        'kode_unik' => $content->order->kode_unik
    ]);
    }



    /* ============================================================
     | DOWNLOAD FILE KONTEN
     ============================================================ */
    public function download($id)
    {
        $content = OrderContent::findOrFail($id);

        if (!$content->file_path) {
            abort(404, 'File tidak tersedia.');
        }

        if (!Storage::disk('public')->exists($content->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($content->file_path);
    }
}
