<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ProductionStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressController extends Controller
{
    /**
     * Menampilkan seluruh progres tugas milik siswa.
     */
    public function index(Request $request)
    {
        $tasks = ProductionStudent::with('production.product')
            ->where('student_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('siswa.progress.index', compact('tasks'));
    }

    /**
     * Menampilkan formulir pembaruan progres tugas.
     */
    public function edit(Request $request, int $id)
    {
        $task = ProductionStudent::with('production.product')
            ->where('student_id', $request->user()->id)
            ->findOrFail($id);

        return view('siswa.progress.edit', compact('task'));
    }

    /**
     * Menyimpan pembaruan progres tugas.
     */
    public function update(Request $request, int $id)
    {
        $task = ProductionStudent::where('student_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'work_notes' => ['nullable', 'string', 'max:2000'],
            'submission_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
                'max:10240',
            ],
        ], [
            'progress.required' => 'Persentase progres wajib diisi.',
            'progress.integer' => 'Progres harus berupa angka.',
            'progress.min' => 'Progres minimal 0%.',
            'progress.max' => 'Progres maksimal 100%.',
            'submission_file.mimes' => 'Format file tidak didukung.',
            'submission_file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        if ($request->hasFile('submission_file')) {
            $newFile = $request->file('submission_file')
                ->store('submissions', 'public');

            if ($task->submission_file) {
                Storage::disk('public')->delete($task->submission_file);
            }

            $task->submission_file = $newFile;
        }

        $task->progress = $validated['progress'];
        $task->work_notes = $validated['work_notes'] ?? null;

        if ($task->progress >= 100) {
            $task->status = 'completed';
        } elseif ($task->progress > 0) {
            $task->status = 'in_progress';
        } else {
            $task->status = 'assigned';
        }

        $task->save();

        return redirect()
            ->route('siswa.progress.index')
            ->with('success', 'Progres tugas berhasil diperbarui.');
    }
}
