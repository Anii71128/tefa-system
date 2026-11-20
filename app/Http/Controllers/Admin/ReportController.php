<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Production;
use App\Models\QualityControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Halaman dashboard laporan TEFA.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'preset' => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        */

        $preset = $validated['preset'] ?? 'month';

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        if (!$dateFrom && !$dateTo) {
            switch ($preset) {
                case 'today':
                    $dateFrom = now()->toDateString();
                    $dateTo = now()->toDateString();
                    break;

                case 'week':
                    $dateFrom = now()->startOfWeek()->toDateString();
                    $dateTo = now()->endOfWeek()->toDateString();
                    break;

                case 'year':
                    $dateFrom = now()->startOfYear()->toDateString();
                    $dateTo = now()->endOfYear()->toDateString();
                    break;

                case 'month':
                default:
                    $dateFrom = now()->startOfMonth()->toDateString();
                    $dateTo = now()->endOfMonth()->toDateString();
                    $preset = 'month';
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | QUERY PESANAN
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::with([
            'user',
            'orderItems.product',
        ]);

        if ($dateFrom) {
            $ordersQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $ordersQuery->whereDate('created_at', '<=', $dateTo);
        }

        $orders = (clone $ordersQuery)
            ->latest()
            ->paginate(10, ['*'], 'order_page')
            ->withQueryString();

        $totalOrders = (clone $ordersQuery)->count();

        $paidOrdersQuery = (clone $ordersQuery)
            ->where('payment_status', 'paid');

        $purchasedOrders = (clone $paidOrdersQuery)->count();

        $unpaidOrders = (clone $ordersQuery)
            ->whereIn('payment_status', ['unpaid', 'pending'])
            ->count();

        $inProgressOrders = (clone $ordersQuery)
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->count();

        $totalRevenue = (clone $paidOrdersQuery)
            ->sum('total_amount');

        $averageTransaction = $purchasedOrders > 0
            ? $totalRevenue / $purchasedOrders
            : 0;

        $successPercentage = $totalOrders > 0
            ? ($purchasedOrders / $totalOrders) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | GRAFIK PENJUALAN
        |--------------------------------------------------------------------------
        */

        $salesChart = [];

        $chartStart = $dateFrom
            ? Carbon::parse($dateFrom)->startOfMonth()
            : now()->startOfYear();

        $chartEnd = $dateTo
            ? Carbon::parse($dateTo)->startOfMonth()
            : now()->endOfYear();

        $monthCursor = $chartStart->copy();

        while ($monthCursor <= $chartEnd) {
            $monthLabel = $monthCursor->translatedFormat('M');

            $monthRevenue = Order::query()
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $monthCursor->year)
                ->whereMonth('created_at', $monthCursor->month)
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->whereDate('created_at', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->whereDate('created_at', '<=', $dateTo);
                })
                ->sum('total_amount');

            $salesChart[] = [
                'label' => $monthLabel,
                'month' => $monthCursor->month,
                'year' => $monthCursor->year,
                'revenue' => (float) $monthRevenue,
            ];

            $monthCursor->addMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK PRODUKSI
        |--------------------------------------------------------------------------
        */

        $productionChart = [];

        $monthCursor = $chartStart->copy();

        while ($monthCursor <= $chartEnd) {
            $monthQuantity = Production::query()
                ->whereYear('production_date', $monthCursor->year)
                ->whereMonth('production_date', $monthCursor->month)
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->whereDate('production_date', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->whereDate('production_date', '<=', $dateTo);
                })
                ->sum('quantity');

            $productionChart[] = [
                'label' => $monthCursor->translatedFormat('M'),
                'month' => $monthCursor->month,
                'year' => $monthCursor->year,
                'quantity' => (float) $monthQuantity,
            ];

            $monthCursor->addMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK TRANSAKSI
        |--------------------------------------------------------------------------
        */

        $transactionChart = [];

        $monthCursor = $chartStart->copy();

        while ($monthCursor <= $chartEnd) {
            $monthTransactions = Order::query()
                ->whereYear('created_at', $monthCursor->year)
                ->whereMonth('created_at', $monthCursor->month)
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    $query->whereDate('created_at', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    $query->whereDate('created_at', '<=', $dateTo);
                })
                ->count();

            $transactionChart[] = [
                'label' => $monthCursor->translatedFormat('M'),
                'month' => $monthCursor->month,
                'year' => $monthCursor->year,
                'transactions' => $monthTransactions,
            ];

            $monthCursor->addMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.payment_status', 'paid')
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('orders.created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('orders.created_at', '<=', $dateTo);
            })
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_sales')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RINCIAN PENJUALAN PRODUK
        |--------------------------------------------------------------------------
        */

        $salesDetails = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('orders.payment_status', 'paid')
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('orders.created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('orders.created_at', '<=', $dateTo);
            })
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_sales'),
                DB::raw('SUM(order_items.price * order_items.quantity) as calculated_sales')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sales')
            ->get();

        $totalSalesDetails = $salesDetails->sum('total_sales');

        /*
        |--------------------------------------------------------------------------
        | TOTAL PRODUKSI
        |--------------------------------------------------------------------------
        */

        $productionsQuery = Production::query();

        if ($dateFrom) {
            $productionsQuery->whereDate('production_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $productionsQuery->whereDate('production_date', '<=', $dateTo);
        }

        $totalProduction = (clone $productionsQuery)
            ->sum('quantity');

        $productions = (clone $productionsQuery)
            ->with(['product', 'guru'])
            ->latest('production_date')
            ->paginate(10, ['*'], 'production_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | QUALITY CONTROL
        |--------------------------------------------------------------------------
        */

        $qualityControlsQuery = QualityControl::with([
            'production.product',
            'production.guru',
            'checkedBy',
        ]);

        if ($dateFrom) {
            $qualityControlsQuery->whereDate('checked_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $qualityControlsQuery->whereDate('checked_at', '<=', $dateTo);
        }

        $qualityControls = (clone $qualityControlsQuery)
            ->latest('checked_at')
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
        | DATA LAPORAN
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
            'averageTransaction',
            'successPercentage',
            'totalProduction',
            'salesChart',
            'productionChart',
            'transactionChart',
            'topProducts',
            'salesDetails',
            'totalSalesDetails',
            'qcPending',
            'qcPassed',
            'qcRevision',
            'dateFrom',
            'dateTo',
            'preset'
        ));
    }

    /**
     * Export laporan ke Excel.
     */
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