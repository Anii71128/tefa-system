<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\QualityControl;
use Illuminate\Http\Request;

class QualityControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qualityControls = QualityControl::with([
            'production.product',
            'checkedBy',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'guru.quality-controls.index',
            compact('qualityControls')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        return view(
            'guru.quality-controls.create',
            compact('productions')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'status' => ['required', 'in:pending,passed,revision'],
            'notes' => ['nullable', 'string'],
            'checked_at' => ['nullable', 'date'],
        ]);

        $validated['checked_by'] = auth()->id();

        QualityControl::create($validated);

        return redirect()
            ->route('guru.quality-controls.index')
            ->with('success', 'Data Quality Control berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityControl $qualityControl)
    {
        $qualityControl->load([
            'production.product',
            'checkedBy',
        ]);

        return view(
            'guru.quality-controls.show',
            compact('qualityControl')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityControl $qualityControl)
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        return view(
            'guru.quality-controls.edit',
            compact('qualityControl', 'productions')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityControl $qualityControl)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'status' => ['required', 'in:pending,passed,revision'],
            'notes' => ['nullable', 'string'],
            'checked_at' => ['nullable', 'date'],
        ]);

        $qualityControl->update($validated);

        return redirect()
            ->route('guru.quality-controls.show', $qualityControl)
            ->with('success', 'Data Quality Control berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityControl $qualityControl)
    {
        $qualityControl->delete();

        return redirect()
            ->route('guru.quality-controls.index')
            ->with('success', 'Data Quality Control berhasil dihapus.');
    }
}
