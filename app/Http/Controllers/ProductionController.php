<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productions = Production::with(['product', 'guru'])
            ->latest()
            ->paginate(10);

        return view('guru.productions.index', compact('productions'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('guru.productions.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'production_date' => ['required', 'date'],
            'status' => [
                'required',
                'in:planned,in_progress,qc,revision,completed',
            ],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['guru_id'] = auth()->id();

        Production::create($validated);

        return redirect()
            ->route('guru.productions.index')
            ->with('success', 'Data produksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Production $production)
    {
        $production->load([
            'product',
            'guru',
        ]);

        return view('guru.productions.show', compact('production'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Production $production)
    {
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('guru.productions.edit', compact(
            'production',
            'products'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Production $production)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'production_date' => ['required', 'date'],
            'status' => [
                'required',
                'in:planned,in_progress,qc,revision,completed',
            ],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $production->update($validated);

        return redirect()
            ->route('guru.productions.show', $production)
            ->with('success', 'Data produksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production)
    {
        $production->delete();

        return redirect()
            ->route('guru.productions.index')
            ->with('success', 'Data produksi berhasil dihapus.');
    }
}
