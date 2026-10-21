<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderItems.product')
            ->latest();

        // Pencarian nomor pesanan atau nama pelanggan
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter status pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query
            ->paginate(10)
            ->withQueryString();

        // Ringkasan transaksi
        $totalTransactions = Order::count();

        $totalRevenue = Order::where('payment_status', 'paid')
            ->sum('total_amount');

        $paidTransactions = Order::where('payment_status', 'paid')
            ->count();

        $unpaidTransactions = Order::where('payment_status', 'unpaid')
            ->count();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalTransactions',
            'totalRevenue',
            'paidTransactions',
            'unpaidTransactions'
        ));
    }

    public function show(Order $order)
    {
        $transaction = $order->load([
            'user',
            'orderItems.product',
        ]);

        return view('admin.transactions.show', compact('transaction'));
    }
}
