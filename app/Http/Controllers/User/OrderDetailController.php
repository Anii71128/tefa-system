<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderDetailController extends Controller
{
    /**
     * Menampilkan detail pesanan milik pelanggan yang login.
     */
    public function show(Request $request, Order $order): View
    {
        abort_unless(
            (int) $order->user_id === (int) $request->user()->id,
            403,
            'Kamu tidak memiliki akses ke pesanan ini.'
        );

        $order->load([
            'orderItems.product',
            'payments',
        ]);

        return view('user.orders.show', compact('order'));
    }
}
