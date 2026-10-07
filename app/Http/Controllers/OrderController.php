<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(10);

        return view('guru.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderItems.product',
        ]);

        return view('guru.orders.show', compact('order'));
    }
    public function approve(Order $order)
    {
        $order->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('guru.orders.show', $order)
            ->with('success', 'Pesanan berhasil disetujui.');
    }
    public function reject(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()
                ->route('guru.orders.show', $order)
                ->with('error', 'Pesanan tidak dapat ditolak karena sudah diproses.');
        }

        $order->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('guru.orders.show', $order)
            ->with('success', 'Pesanan berhasil ditolak.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
