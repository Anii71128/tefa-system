<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ProductionStudent;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas milik siswa yang sedang login.
     */
    public function index(Request $request)
    {
        $tasks = ProductionStudent::with([
            'production.product',
            'production.guru',
        ])
            ->where('student_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('siswa.tasks.index', compact('tasks'));
    }

    /**
     * Menampilkan detail salah satu tugas milik siswa.
     */
    public function show(Request $request, int $id)
    {
        $task = ProductionStudent::with([
            'production.product',
            'production.guru',
        ])
            ->where('student_id', $request->user()->id)
            ->findOrFail($id);

        return view('siswa.tasks.show', compact('task'));
    }
}
