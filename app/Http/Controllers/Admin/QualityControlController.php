<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\QualityControl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QualityControlController extends Controller
{
    /**
     * Menampilkan daftar pemeriksaan kualitas.
     */
    public function index(Request $request)
    {
        $query = QualityControl::with([
            'production.product',
            'checkedBy',
        ])->latest();

        // Pencarian berdasarkan nama produk atau pemeriksa.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('production.product', function ($productQuery) use ($search) {
                    $productQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                })->orWhereHas('checkedBy', function ($userQuery) use ($search) {
                    $userQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        // Filter berdasarkan status QC.
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $qualityControls = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.quality-controls.index',
            compact('qualityControls')
        );
    }

    /**
     * Menampilkan form tambah pemeriksaan.
     */
    public function create()
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        $inspectors = User::whereIn('role', ['admin', 'guru'])
            ->orderBy('name')
            ->get();

        return view(
            'admin.quality-controls.create',
            compact('productions', 'inspectors')
        );
    }

    /**
     * Menyimpan pemeriksaan kualitas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => [
                'required',
                'exists:productions,id',
            ],
            'checked_by' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereIn('role', ['admin', 'guru']),
            ],
            'status' => ['required', Rule::in(['pending', 'passed', 'revision'])],
            'notes' => ['nullable', 'string'],
            'checked_at' => ['nullable', 'date'],
        ]);

        QualityControl::create($validated);

        return redirect()
            ->route('admin.quality-controls.index')
            ->with(
                'success',
                'Data Quality Control berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail pemeriksaan.
     */
    public function show(QualityControl $qualityControl)
    {
        $qualityControl->load([
            'production.product',
            'checkedBy',
        ]);

        return view(
            'admin.quality-controls.show',
            compact('qualityControl')
        );
    }

    /**
     * Menampilkan form edit pemeriksaan.
     */
    public function edit(QualityControl $qualityControl)
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        $inspectors = User::whereIn('role', ['admin', 'guru'])
            ->orderBy('name')
            ->get();

        return view(
            'admin.quality-controls.edit',
            compact('qualityControl', 'productions', 'inspectors')
        );
    }

    /**
     * Memperbarui pemeriksaan kualitas.
     */
    public function update(
        Request $request,
        QualityControl $qualityControl
    ) {
        $validated = $request->validate([
            'production_id' => [
                'required',
                'exists:productions,id',
            ],
            'checked_by' => [
                'required',
                Rule::exists('users', 'id')
                    ->whereIn('role', ['admin', 'guru']),
            ],
            'status' => ['required', Rule::in(['pending', 'passed', 'revision'])],
            'notes' => ['nullable', 'string'],
            'checked_at' => ['nullable', 'date'],
        ]);

        $qualityControl->update($validated);

        return redirect()
            ->route('admin.quality-controls.index')
            ->with(
                'success',
                'Data Quality Control berhasil diperbarui.'
            );
    }

    /**
     * Menghapus data pemeriksaan.
     */
    public function destroy(QualityControl $qualityControl)
    {
        $qualityControl->delete();

        return redirect()
            ->route('admin.quality-controls.index')
            ->with(
                'success',
                'Data Quality Control berhasil dihapus.'
            );
    }
}
