<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\ProductionStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductionStudentController extends Controller
{
    /**
     * Menampilkan semua penugasan siswa.
     */
    public function index(Request $request)
    {
        $query = ProductionStudent::with([
            'production.product',
            'student',
        ])->latest();

        // Pencarian berdasarkan nama produk atau nama siswa.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('production.product', function ($productQuery) use ($search) {
                    $productQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                })->orWhereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        // Filter berdasarkan status penugasan.
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.production-students.index',
            compact('assignments')
        );
    }

    /**
     * Menampilkan form tambah penugasan.
     */
    public function create()
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view(
            'admin.production-students.create',
            compact('productions', 'students')
        );
    }

    /**
     * Menyimpan penugasan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => [
                'required',
                'exists:productions,id',
            ],
            'student_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('role', 'siswa'),
            ],
            'task' => [
                'required',
                'string',
                'max:255',
            ],
            'deadline' => [
                'nullable',
                'date',
            ],
            'status' => [
                'required',
                Rule::in([
                    'assigned',
                    'in_progress',
                    'completed',
                ]),
            ],
        ]);

        ProductionStudent::create($validated);

        return redirect()
            ->route('admin.production-students.index')
            ->with(
                'success',
                'Penugasan siswa berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail penugasan.
     */
    public function show(ProductionStudent $productionStudent)
    {
        $productionStudent->load([
            'production.product',
            'student',
        ]);

        return view(
            'admin.production-students.show',
            compact('productionStudent')
        );
    }

    /**
     * Menampilkan form edit penugasan.
     */
    public function edit(ProductionStudent $productionStudent)
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view(
            'admin.production-students.edit',
            compact(
                'productionStudent',
                'productions',
                'students'
            )
        );
    }

    /**
     * Memperbarui penugasan.
     */
    public function update(
        Request $request,
        ProductionStudent $productionStudent
    ) {
        $validated = $request->validate([
            'production_id' => [
                'required',
                'exists:productions,id',
            ],
            'student_id' => [
                'required',
                Rule::exists('users', 'id')
                    ->where('role', 'siswa'),
            ],
            'task' => [
                'required',
                'string',
                'max:255',
            ],
            'deadline' => [
                'nullable',
                'date',
            ],
            'status' => [
                'required',
                Rule::in([
                    'assigned',
                    'in_progress',
                    'completed',
                ]),
            ],
        ]);

        $productionStudent->update($validated);

        return redirect()
            ->route('admin.production-students.index')
            ->with(
                'success',
                'Penugasan siswa berhasil diperbarui.'
            );
    }

    /**
     * Menghapus penugasan.
     */
    public function destroy(ProductionStudent $productionStudent)
    {
        $productionStudent->delete();

        return redirect()
            ->route('admin.production-students.index')
            ->with(
                'success',
                'Penugasan siswa berhasil dihapus.'
            );
    }
}
