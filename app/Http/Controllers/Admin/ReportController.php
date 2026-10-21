<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Production;
use App\Models\QualityControl;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // Validasi filter tanggal.
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],
        ]);

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | 1. Laporan Pesanan
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::with([
            'user',
            'orderItems.product',
            'payments',
        ])->latest();

        $summaryQuery = Order::query();

        if ($dateFrom) {
            $ordersQuery->whereDate('created_at', '>=', $dateFrom);
            $summaryQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $ordersQuery->whereDate('created_at', '<=', $dateTo);
            $summaryQuery->whereDate('created_at', '<=', $dateTo);
        }

        $orders = $ordersQuery
            ->paginate(10)
            ->withQueryString();

        $totalOrders = (clone $summaryQuery)->count();

        $purchasedOrders = (clone $summaryQuery)
            ->where('payment_status', 'paid')
            ->count();

        $unpaidOrders = (clone $summaryQuery)
            ->where('payment_status', 'unpaid')
            ->count();

        $inProgressOrders = (clone $summaryQuery)
            ->whereIn('status', [
                'pending',
                'approved',
                'processing',
            ])
            ->count();

        $totalRevenue = (clone $summaryQuery)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | 2. Laporan Produksi
        |--------------------------------------------------------------------------
        */

        $productionsQuery = Production::with([
            'product',
            'guru',
        ])->latest();

        if ($dateFrom) {
            $productionsQuery->whereDate(
                'production_date',
                '>=',
                $dateFrom
            );
        }

        if ($dateTo) {
            $productionsQuery->whereDate(
                'production_date',
                '<=',
                $dateTo
            );
        }

        $productions = $productionsQuery
            ->paginate(10, ['*'], 'production_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | 3. Laporan Quality Control (QC)
        |--------------------------------------------------------------------------
        */

        $qualityControlsQuery = QualityControl::with([
            'production.product',
            'production.guru',
            'checkedBy',
        ])->latest();

        if ($dateFrom) {
            $qualityControlsQuery->whereDate(
                'checked_at',
                '>=',
                $dateFrom
            );
        }

        if ($dateTo) {
            $qualityControlsQuery->whereDate(
                'checked_at',
                '<=',
                $dateTo
            );
        }

        $qualityControls = $qualityControlsQuery
            ->paginate(10, ['*'], 'qc_page')
            ->withQueryString();

        $qcPending = (clone $qualityControlsQuery)
            ->where('status', 'pending')
            ->count();

        $qcPassed = (clone $qualityControlsQuery)
            ->where('status', 'passed')
            ->count();

        $qcRevision = (clone $qualityControlsQuery)
            ->where('status', 'revision')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 4. Kirim Data ke Halaman Laporan
        |--------------------------------------------------------------------------
        */

        return view('admin.reports.index', compact(
            'orders',
            'productions',
            'qualityControls',
            'totalOrders',
            'purchasedOrders',
            'unpaidOrders',
            'inProgressOrders',
            'totalRevenue',
            'qcPending',
            'qcPassed',
            'qcRevision',
        ));
    }
    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        $filename = 'laporan-tefa-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new ReportsExport($dateFrom, $dateTo),
            $filename
        );
    }
}
