<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{
    public function create(
        Request $request,
        Order $order,
        MidtransService $midtrans
    ): JsonResponse {
        // Pastikan pesanan milik pengguna yang login.
        abort_unless(
            $request->user()
            && (int) $order->user_id === (int) $request->user()->id,
            403,
            'Kamu tidak memiliki akses ke pesanan ini.'
        );

        // Pesanan yang sudah dibayar tidak perlu dibayar lagi.
        if ($order->payment_status === 'paid') {
            return response()->json([
                'message' => 'Pesanan ini sudah dibayar.',
            ], 422);
        }
        // Pembayaran hanya tersedia untuk pesanan yang disetujui guru.
        if ($order->status !== 'approved') {
            return response()->json([
                'message' => 'Pesanan harus disetujui guru sebelum dibayar.',
            ], 422);
        }
        // Cegah pembuatan pembayaran baru jika masih ada pembayaran pending.

        $pendingPayment = Payment::where('order_id', $order->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($pendingPayment) {
            if ($pendingPayment->transaction_token) {
                return response()->json([
                    'message' => 'Pembayaran untuk pesanan ini sudah tersedia.',
                    'snap_token' => $pendingPayment->transaction_token,
                    'gateway_order_id' => $pendingPayment->gateway_order_id,
                ]);
            }

            // Record pending tanpa token tidak dapat dipakai untuk membuka Snap.
            $pendingPayment->update([
                'status' => 'failed',
            ]);
        }


        if ($pendingPayment && $pendingPayment->transaction_token) {
            return response()->json([
                'message' => 'Pembayaran untuk pesanan ini sudah tersedia.',
                'snap_token' => $pendingPayment->transaction_token,
                'gateway_order_id' => $pendingPayment->gateway_order_id,
            ]);
        }
        $amount = (float) $order->total_amount;

        if (
            !is_finite($amount) ||
            $amount <= 0 ||
            $amount !== round($amount)
        ) {
            return response()->json([
                'message' => 'Total pesanan harus berupa nominal rupiah bulat yang valid.',
            ], 422);
        }

        // Gunakan ID unik untuk setiap percobaan pembayaran.
        $gatewayOrderId = 'TEFA-' . Str::upper(Str::uuid());

        $payment = Payment::create([
            'order_id' => $order->id,
            'gateway' => 'midtrans',
            'gateway_order_id' => $gatewayOrderId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        try {
            $payload = [
                'transaction_details' => [
                    'order_id' => $gatewayOrderId,
                    'gross_amount' => (int) $amount,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
            ];

            $snapToken = $midtrans->createSnapToken($payload);

            $payment->update([
                'transaction_token' => $snapToken,
            ]);

            return response()->json([
                'message' => 'Snap Token berhasil dibuat.',
                'snap_token' => $snapToken,
                'gateway_order_id' => $gatewayOrderId,
            ]);
        } catch (Throwable $e) {
            report($e);

            $payment->update([
                'status' => 'failed',
            ]);

            return response()->json([
                'message' => 'Gagal membuat pembayaran. Silakan coba lagi.',
            ], 502);
        }
    }
}
