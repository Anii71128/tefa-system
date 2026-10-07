<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Production;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evaluations = Evaluation::with([
            'production.product',
            'student',
            'evaluatedBy',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'guru.evaluations.index',
            compact('evaluations')
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
            'guru.evaluations.create',
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
            'skill_score' => ['required', 'integer', 'min:0', 'max:100'],
            'discipline_score' => ['required', 'integer', 'min:0', 'max:100'],
            'quality_score' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['evaluated_by'] = auth()->id();

        Evaluation::create($validated);

        return redirect()
            ->route('guru.evaluations.index')
            ->with('success', 'Evaluasi siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        $evaluation->load([
            'production.product',
            'student',
            'evaluatedBy',
        ]);

        return view(
            'guru.evaluations.show',
            compact('evaluation')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        $productions = Production::with('product')
            ->latest()
            ->get();

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view(
            'guru.evaluations.edit',
            compact('evaluation', 'productions', 'students')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'student_id' => ['required', 'exists:users,id'],
            'skill_score' => ['required', 'integer', 'min:0', 'max:100'],
            'discipline_score' => ['required', 'integer', 'min:0', 'max:100'],
            'quality_score' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $evaluation->update($validated);

        return redirect()
            ->route('guru.evaluations.show', $evaluation)
            ->with('success', 'Evaluasi siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(Evaluation $evaluation)
{
    $evaluation->delete();

    return redirect()
        ->route('guru.evaluations.index')
        ->with('success', 'Evaluasi siswa berhasil dihapus.');
}
}
