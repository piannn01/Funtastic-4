<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::latest()->paginate(10);

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:150',
            'category'     => 'nullable|string|max:100',
            'client'       => 'nullable|string|max:150',
            'completed_at' => 'nullable|date',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('portfolios', 'public');
        }

        Portfolio::create($validated);

        return redirect()
            ->route('admin.portfolio.index')
            ->with('success', 'Portfolio berhasil ditambahkan!');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:150',
            'category'     => 'nullable|string|max:100',
            'client'       => 'nullable|string|max:150',
            'completed_at' => 'nullable|date',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {

            if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
                Storage::disk('public')->delete($portfolio->image);
            }

            $validated['image'] = $request->file('image')->store('portfolios', 'public');
        }

        $portfolio->update($validated);

        return redirect()
            ->route('admin.portfolio.index')
            ->with('success', 'Portfolio berhasil diperbarui!');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
            Storage::disk('public')->delete($portfolio->image);
        }

        $portfolio->delete();

        return redirect()
            ->route('admin.portfolio.index')
            ->with('success', 'Portfolio berhasil dihapus!');
    }
}
