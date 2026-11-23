<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ProductionStudent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->user()->id;

        // Mengambil semua tugas milik siswa yang sedang login.
        $tasks = ProductionStudent::with([
            'production.product',
            'production.guru',
        ])
            ->where('student_id', $studentId)
            ->latest()
            ->get();

        // Ringkasan tugas.
        $activeTasks = $tasks->whereIn('status', [
            'assigned',
            'in_progress',
        ]);

        $completedTasks = $tasks->where('status', 'completed');

        // Menghitung rata-rata progres seluruh tugas.
        $averageProgress = $tasks->isNotEmpty()
            ? round($tasks->avg('progress'))
            : 0;

        // Tugas yang sudah melewati deadline dan belum selesai.
        $overdueTasks = $activeTasks->filter(function ($task) {
            return $task->deadline
                && $task->deadline->lt(today());
        });

        // Tugas dengan deadline terdekat.
        $upcomingTasks = $activeTasks
            ->filter(function ($task) {
                return $task->deadline !== null;
            })
            ->sortBy('deadline')
            ->take(5);

        // Notifikasi tugas yang deadline-nya dekat atau sudah lewat.
        $notifications = $activeTasks
            ->filter(function ($task) {
                return $task->deadline
                    && $task->deadline->lte(now()->addDays(3));
            })
            ->sortBy('deadline')
            ->take(5);

        return view('siswa.dashboard', compact(
            'tasks',
            'activeTasks',
            'completedTasks',
            'averageProgress',
            'overdueTasks',
            'upcomingTasks',
            'notifications'
        ));
    }
}
