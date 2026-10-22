<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan katalog produk yang aktif dan tersedia.
     */
    public function index(): View
    {
        $products = Product::query()
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        return view('user.orders.index', compact('products'));
    }

    /**
     * Membuat pesanan pelanggan.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            $orderItems = [];
            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (
                    !$product ||
                    $product->status !== 'active' ||
                    $product->stock < $item['quantity']
                ) {
                    throw ValidationException::withMessages([
                        'items' => 'Produk tidak tersedia atau stok tidak mencukupi. Silakan periksa kembali.',
                    ]);
                }

                $priceInCents = (int) round(
                    (float) $product->price * 100
                );

                $subtotalInCents = $priceInCents * (int) $item['quantity'];

                $totalAmount += $subtotalInCents;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => (int) $item['quantity'],
                    'price' => $priceInCents / 100,
                    'subtotal' => $subtotalInCents / 100,
                ];
            }

            if ($totalAmount <= 0) {
                throw ValidationException::withMessages([
                    'items' => 'Total pesanan harus lebih dari nol.',
                ]);
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => 'TEFA-' . Str::upper(
                    (string) Str::uuid()
                ),
                'total_amount' => $totalAmount / 100,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pesanan berhasil dibuat dan menunggu persetujuan guru.',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                ],
            ], 201);
        }

        return redirect()
            ->route('user.orders.show', $order)
            ->with(
                'success',
                'Pesanan berhasil dibuat dan menunggu persetujuan guru.'
            );
    }
}
