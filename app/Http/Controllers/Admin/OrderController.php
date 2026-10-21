<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')
            ->withCount('orderItems')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan.
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderItems.product',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'processing',
                    'completed',
                ]),
            ],
            'payment_status' => [
                'required',
                Rule::in(['unpaid', 'paid']),
            ],
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
