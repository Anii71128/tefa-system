<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionStudent;
use App\Models\User;
use Illuminate\Http\Request;

class ProductionStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productionStudents = ProductionStudent::with([
            'production.product',
            'student',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'guru.production-students.index',
            compact('productionStudents')
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

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view(
            'guru.production-students.create',
            compact('productions', 'students')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'student_id' => ['required', 'exists:users,id'],
            'task' => ['required', 'string', 'max:1000'],
            'deadline' => ['nullable', 'date'],
            'status' => [
                'required',
                'in:assigned,in_progress,completed',
            ],
        ]);

        ProductionStudent::create($validated);

        return redirect()
            ->route('guru.production-students.index')
            ->with('success', 'Penugasan siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionStudent $productionStudent)
    {
        $productionStudent->load([
            'production.product',
            'student',
        ]);

        return view(
            'guru.production-students.show',
            compact('productionStudent')
        );
    }
    /**
     * Show the form for editing the specified resource.
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
            'guru.production-students.edit',
            compact('productionStudent', 'productions', 'students')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionStudent $productionStudent)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'student_id' => ['required', 'exists:users,id'],
            'task' => ['required', 'string', 'max:1000'],
            'deadline' => ['nullable', 'date'],
            'status' => [
                'required',
                'in:assigned,in_progress,completed',
            ],
        ]);

        $productionStudent->update($validated);

        return redirect()
            ->route('guru.production-students.show', $productionStudent)
            ->with('success', 'Penugasan siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionStudent $productionStudent)
    {
        $productionStudent->delete();

        return redirect()
            ->route('guru.production-students.index')
            ->with('success', 'Penugasan siswa berhasil dihapus.');
    }
}
