<?php

use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductionStudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualityControlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => abort(403),
    };
})->middleware('auth')->name('dashboard');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:guru'])->group(function () {

    Route::get('/guru', function () {
        return view('guru.dashboard');
    })->name('guru.dashboard');

    Route::resource('guru/products', ProductController::class)
        ->names('guru.products');

    Route::patch(
        'guru/orders/{order}/approve',
        [OrderController::class, 'approve']
    )->name('guru.orders.approve');
    Route::patch(
        'guru/orders/{order}/reject',
        [OrderController::class, 'reject']
    )->name('guru.orders.reject');

    Route::resource('guru/orders', OrderController::class)
        ->names('guru.orders');
    Route::resource('guru/productions', ProductionController::class)
        ->names('guru.productions');
    Route::resource('guru/production-students', ProductionStudentController::class)
        ->names('guru.production-students');
    Route::resource('guru/quality-controls', QualityControlController::class)
        ->names('guru.quality-controls');
    Route::resource('guru/evaluations', EvaluationController::class)
        ->names('guru.evaluations');
});
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});


require __DIR__ . '/auth.php';
