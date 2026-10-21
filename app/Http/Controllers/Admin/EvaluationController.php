<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Production;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = Evaluation::with([
            'production.product',
            'student',
            'evaluatedBy',
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('production.product', function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $evaluations = $query->paginate(10)->withQueryString();

        return view('admin.evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $productions = Production::with('product')->latest()->get();

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        $evaluators = User::whereIn('role', ['admin', 'guru'])
            ->orderBy('name')
            ->get();

        return view('admin.evaluations.create', compact(
            'productions',
            'students',
            'evaluators'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'student_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'siswa'),
            ],
            'skill_score' => ['required', 'integer', 'between:0,100'],
            'discipline_score' => ['required', 'integer', 'between:0,100'],
            'quality_score' => ['required', 'integer', 'between:0,100'],
            'notes' => ['nullable', 'string'],
            'evaluated_by' => [
                'required',
                Rule::exists('users', 'id')->whereIn('role', ['admin', 'guru']),
            ],
        ]);

        Evaluation::create($validated);

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluasi siswa berhasil ditambahkan.');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load([
            'production.product',
            'student',
            'evaluatedBy',
        ]);

        return view('admin.evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $productions = Production::with('product')->latest()->get();

        $students = User::where('role', 'siswa')
            ->orderBy('name')
            ->get();

        $evaluators = User::whereIn('role', ['admin', 'guru'])
            ->orderBy('name')
            ->get();

        return view('admin.evaluations.edit', compact(
            'evaluation',
            'productions',
            'students',
            'evaluators'
        ));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'student_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'siswa'),
            ],
            'skill_score' => ['required', 'integer', 'between:0,100'],
            'discipline_score' => ['required', 'integer', 'between:0,100'],
            'quality_score' => ['required', 'integer', 'between:0,100'],
            'notes' => ['nullable', 'string'],
            'evaluated_by' => [
                'required',
                Rule::exists('users', 'id')->whereIn('role', ['admin', 'guru']),
            ],
        ]);

        $evaluation->update($validated);

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluasi siswa berhasil diperbarui.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluasi siswa berhasil dihapus.');
    }
}
