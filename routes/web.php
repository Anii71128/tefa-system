
<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductionController as AdminProductionController;
use App\Http\Controllers\Admin\ProductionStudentController as AdminProductionStudentController;
use App\Http\Controllers\Admin\QualityControlController as AdminQualityControlController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductionStudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualityControlController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductionController as AdminProductionController;
use App\Http\Controllers\Admin\ProductionStudentController as AdminProductionStudentController;
use App\Http\Controllers\Admin\QualityControlController as AdminQualityControlController;
use App\Http\Controllers\Admin\EvaluationController as AdminEvaluationController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\OrderDetailController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\EvaluationController as SiswaEvaluationController;
use App\Http\Controllers\Siswa\HistoryController;
use App\Http\Controllers\Siswa\ProgressController;
use App\Http\Controllers\Siswa\TaskController;

use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\OrderDetailController;
use App\Http\Controllers\User\UserProfileController;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/orders/{order}/payment', [PaymentController::class, 'create'])
        ->name('orders.payment.create');
});

Route::get('/dashboard', function () {
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => abort(403),
    };

})->middleware('auth')->name('dashboard');
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post(
        '/orders/{order}/payment',
        [PaymentController::class, 'create']
    )->name('orders.payment.create');

});

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/admin', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Pengaturan Admin
    // Pengaturan Admin
    Route::get('/admin/settings', [SettingsController::class, 'index'])
        ->name('admin.settings.index');

    Route::put('/admin/settings/profile', [SettingsController::class, 'updateProfile'])
        ->name('admin.settings.profile.update');

    Route::put('/admin/settings/password', [SettingsController::class, 'updatePassword'])
        ->name('admin.settings.password.update');

    Route::post('/admin/settings/backup', [SettingsController::class, 'backup'])
    ->name('admin.settings.backup');

    Route::put('/admin/settings/system', [SettingsController::class, 'updateSystem'])
    ->name('admin.settings.system.update');

    // Produk
    Route::resource('admin/products', AdminProductController::class)
        ->names('admin.products');
    Route::resource('admin/categories', CategoryController::class)
        ->names('admin.categories');

    Route::get('admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::get('admin/orders/{order}', [AdminOrderController::class, 'show'])
        ->name('admin.orders.show');

    Route::patch('admin/orders/{order}/status', [
        AdminOrderController::class,
        'updateStatus',
    ])->name('admin.orders.update-status');
    // Produksi
    Route::resource('admin/productions', AdminProductionController::class)
        ->names('admin.productions');

    // Siswa Produksi
    Route::resource('admin/production-students', AdminProductionStudentController::class)
        ->names('admin.production-students');

    // Quality Control
    Route::resource('admin/quality-controls', AdminQualityControlController::class)
        ->names('admin.quality-controls');

    // Evaluasi
    Route::resource('admin/evaluations', AdminEvaluationController::class)
        ->names('admin.evaluations');
    Route::resource('admin/productions', AdminProductionController::class)
        ->names('admin.productions');

    Route::resource(
        'admin/production-students',
        AdminProductionStudentController::class
    )->names('admin.production-students');

    Route::resource('admin/quality-controls', AdminQualityControlController::class)
        ->names('admin.quality-controls');

    Route::resource('admin/evaluations', AdminEvaluationController::class)
        ->names('admin.evaluations');

    Route::get('admin/transactions', [AdminTransactionController::class, 'index'])
        ->name('admin.transactions.index');

    Route::get('admin/transactions/{order}', [AdminTransactionController::class, 'show'])
        ->name('admin.transactions.show');
    // Pembayaran
    Route::get('admin/payments', [AdminPaymentController::class, 'index'])
        ->name('admin.payments.index');

    Route::get('admin/payments/{payment}', [AdminPaymentController::class, 'show'])


    Route::get('admin/payments', [
        AdminPaymentController::class,
        'index',
    ])->name('admin.payments.index');

    Route::get('admin/payments/{payment}', [
        AdminPaymentController::class,
        'show',
    ])->name('admin.payments.show');

    // Laporan
    Route::get('admin/reports/export/excel', [
        AdminReportController::class,
        'exportExcel',
    ])->name('admin.reports.export.excel');
    Route::get('admin/reports', [AdminReportController::class, 'index'])

    Route::get('admin/reports', [
        AdminReportController::class,
        'index',
    ])->name('admin.reports.index');

});


Route::middleware(['auth', 'role:guru'])->group(function () {

    Route::get('/guru', function () {
        return view('guru.dashboard');
    })->name('guru.dashboard');

    // Produk Guru
    Route::resource('guru/products', ProductController::class)
        ->names('guru.products');
    // Persetujuan Pesanan
    Route::patch('guru/orders/{order}/approve', [OrderController::class, 'approve'])
    Route::patch(
        'guru/orders/{order}/approve',
        [OrderController::class, 'approve']
    )->name('guru.orders.approve');

    Route::patch(
        'guru/orders/{order}/reject',
        [OrderController::class, 'reject']
    )->name('guru.orders.reject');

    Route::patch('guru/orders/{order}/reject', [OrderController::class, 'reject'])
        ->name('guru.orders.reject');

    // Pesanan
    Route::resource('guru/orders', OrderController::class)
        ->names('guru.orders');
    Route::resource('guru/productions', ProductionController::class)
        ->names('guru.productions');

    // Siswa Produksi
    Route::resource('guru/production-students', ProductionStudentController::class)
        ->names('guru.production-students');

    // Quality Control
    Route::resource('guru/quality-controls', QualityControlController::class)
        ->names('guru.quality-controls');

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

    // Katalog Produk
    Route::get('/user/products', [UserOrderController::class, 'index'])
        ->name('user.orders.index');

    // Membuat Pesanan
    Route::post('/user/orders', [UserOrderController::class, 'store'])
        ->name('user.orders.store');

    // Detail Pesanan
    Route::get('/user/orders/{order}', [OrderDetailController::class, 'show'])
        ->name('user.orders.show');

    // Riwayat Pesanan
    Route::get('/user/orders', function (\Illuminate\Http\Request $request) {

        $orders = \App\Models\Order::query()

Route::middleware(['auth', 'role:siswa'])->group(function () {

    Route::get('/siswa', [
        SiswaDashboardController::class,
        'index',
    ])->name('siswa.dashboard');

    Route::get('/siswa/tugas', [TaskController::class, 'index'])
        ->name('siswa.tasks.index');

    Route::get('/siswa/tugas/{id}', [TaskController::class, 'show'])
        ->name('siswa.tasks.show');

    Route::get('/siswa/progres', [ProgressController::class, 'index'])
        ->name('siswa.progress.index');

    Route::get('/siswa/progres/{id}', [ProgressController::class, 'edit'])
        ->name('siswa.progress.edit');

    Route::put('/siswa/progres/{id}', [ProgressController::class, 'update'])
        ->name('siswa.progress.update');

    Route::get('/siswa/riwayat', [HistoryController::class, 'index'])
        ->name('siswa.history.index');

    Route::get('/siswa/evaluasi', [SiswaEvaluationController::class, 'index'])
        ->name('siswa.evaluations.index');

    Route::get('/siswa/evaluasi/{id}', [SiswaEvaluationController::class, 'show'])
        ->name('siswa.evaluations.show');

});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/products', [UserOrderController::class, 'index'])
        ->name('user.orders.index');

    Route::post('/user/orders', [UserOrderController::class, 'store'])
        ->name('user.orders.store');

    Route::get('/user/orders/{order}', [
        OrderDetailController::class,
        'show',
    ])->name('user.orders.show');
    Route::get('/user/orders', function (Request $request) {

        $orders = Order::query()

            ->where('user_id', $request->user()->id)
            ->with([
                'orderItems.product',
                'payments:id,order_id,status,amount,payment_method,created_at',
            ])
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);

    })->name('user.orders.list');
});


require __DIR__ . '/auth.php';

Route::post('/midtrans/notification', [
    MidtransNotificationController::class,
    'handle',
])->name('midtrans.notification');
    Route::post('/user/profile', [
        UserProfileController::class,
        'update',
    ]);

});

require __DIR__ . '/auth.php';
Route::post(
    '/midtrans/notification',
    [MidtransNotificationController::class, 'handle']
)->name('midtrans.notification');
