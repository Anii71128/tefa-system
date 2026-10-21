<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Production;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductionController extends Controller
{
    /**
     * Menampilkan daftar produksi.
     */
    public function index(Request $request)
    {
        $query = Production::with(['product', 'guru'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('guru', function ($guruQuery) use ($search) {
                    $guruQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $productions = $query->paginate(10)->withQueryString();

        return view('admin.productions.index', compact('productions'));
    }

    /**
     * Menampilkan formulir tambah produksi.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $gurus = User::where('role', 'guru')->orderBy('name')->get();

        return view('admin.productions.create', compact('products', 'gurus'));
    }

    /**
     * Menyimpan produksi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'guru_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'guru'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],
            'production_date' => ['required', 'date'],
            'status' => [
                'required',
                Rule::in([
                    'planned',
                    'in_progress',
                    'qc',
                    'revision',
                    'completed',
                ]),
            ],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        Production::create($validated);

        return redirect()
            ->route('admin.productions.index')
            ->with('success', 'Data produksi berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail produksi.
     */
    public function show(Production $production)
    {
        $production->load([
            'product',
            'guru',
        ]);

        return view('admin.productions.show', compact('production'));
    }

    /**
     * Menampilkan formulir edit produksi.
     */
    public function edit(Production $production)
    {
        $products = Product::orderBy('name')->get();
        $gurus = User::where('role', 'guru')->orderBy('name')->get();

        return view(
            'admin.productions.edit',
            compact('production', 'products', 'gurus')
        );
    }

    /**
     * Memperbarui data produksi.
     */
    public function update(Request $request, Production $production)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'guru_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'guru'),
            ],
            'quantity' => ['required', 'integer', 'min:1'],
            'production_date' => ['required', 'date'],
            'status' => [
                'required',
                Rule::in([
                    'planned',
                    'in_progress',
                    'qc',
                    'revision',
                    'completed',
                ]),
            ],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $production->update($validated);

        return redirect()
            ->route('admin.productions.index')
            ->with('success', 'Data produksi berhasil diperbarui.');
    }

    /**
     * Menghapus data produksi.
     */
    public function destroy(Production $production)
    {
        $production->delete();

        return redirect()
            ->route('admin.productions.index')
            ->with('success', 'Data produksi berhasil dihapus.');
    }
}
