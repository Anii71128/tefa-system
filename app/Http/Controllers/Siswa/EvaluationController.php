<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Menampilkan evaluasi milik siswa yang sedang login.
     */
    public function index(Request $request)
    {
        $evaluations = Evaluation::with([
            'production.product',
            'evaluatedBy',
        ])
            ->where('student_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        $totalEvaluations = Evaluation::where(
            'student_id',
            $request->user()->id
        )->count();

        $averageScore = Evaluation::where(
            'student_id',
            $request->user()->id
        )
            ->get()
            ->map(function ($evaluation) {
                return (
                    $evaluation->skill_score +
                    $evaluation->discipline_score +
                    $evaluation->quality_score
                ) / 3;
            })
            ->avg();

        return view('siswa.evaluations.index', compact(
            'evaluations',
            'totalEvaluations',
            'averageScore'
        ));
    }

    /**
     * Menampilkan detail evaluasi.
     */
    public function show(Request $request, int $id)
    {
        $evaluation = Evaluation::with([
            'production.product',
            'evaluatedBy',
        ])
            ->where('student_id', $request->user()->id)
            ->findOrFail($id);

        return view('siswa.evaluations.show', compact('evaluation'));
    }
}
