<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Payment::with(['order.user'])
            ->latest();

        // Pencarian ID pembayaran, nomor pesanan, atau pelanggan.
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('gateway_order_id', 'like', "%{$search}%")
                    ->orWhere('gateway_transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter status pembayaran.
        if (
            in_array($request->input('status'), [
                'pending',
                'paid',
                'failed',
            ], true)
        ) {
            $query->where('status', $request->input('status'));
        }

        // Filter tanggal pembayaran dibuat.
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $payments = $query->paginate(10)->withQueryString();

        // Ringkasan berdasarkan data pembayaran.
        $totalPayments = Payment::count();

        $paidPayments = Payment::where('status', 'paid')->count();

        $pendingPayments = Payment::where('status', 'pending')->count();

        $failedPayments = Payment::where('status', 'failed')->count();

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        return view('admin.payments.index', compact(
            'payments',
            'totalPayments',
            'paidPayments',
            'pendingPayments',
            'failedPayments',
            'totalRevenue'
        ));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['order.user', 'order.orderItems.product']);

        return view('admin.payments.show', compact('payment'));
    }
}
