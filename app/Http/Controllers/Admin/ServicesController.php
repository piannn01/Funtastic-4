<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServicesController extends Controller
{
    /**
     * Tampilkan semua layanan
     */
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Form tambah layanan
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Simpan layanan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'feed' => 'nullable|integer',
            'stories' => 'nullable|integer',
            'video_reels' => 'nullable|integer',
            'duration' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required'
        ]);

        Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'feed' => $request->feed ?? 0,
            'stories' => $request->stories ?? 0,
            'video_reels' => $request->video_reels ?? 0,
            'duration' => $request->duration,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Form edit layanan
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update layanan
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'feed' => 'nullable|integer',
            'stories' => 'nullable|integer',
            'video_reels' => 'nullable|integer',
            'duration' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required'
        ]);

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'feed' => $request->feed ?? 0,
            'stories' => $request->stories ?? 0,
            'video_reels' => $request->video_reels ?? 0,
            'duration' => $request->duration,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Hapus layanan
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }
}
