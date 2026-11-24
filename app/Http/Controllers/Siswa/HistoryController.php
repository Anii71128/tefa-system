<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ProductionStudent;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Menampilkan riwayat seluruh tugas milik siswa.
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

        return view('siswa.history.index', compact('tasks'));
    }
}
