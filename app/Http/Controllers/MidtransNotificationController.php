<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransNotificationController extends Controller
{
    public function handle(
        Request $request,
        \App\Services\MidtransService $midtrans
    ): JsonResponse {
        $data = $request->all();

        $orderId = $data['order_id'] ?? '';
        $statusCode = $data['status_code'] ?? '';
        $grossAmount = $data['gross_amount'] ?? '';
        $signature = $data['signature_key'] ?? '';
        $serverKey = config('midtrans.server_key');

        if (
            !is_string($orderId) ||
            !is_string($statusCode) ||
            !is_string($grossAmount) ||
            !is_string($signature) ||
            !is_string($serverKey) ||
            $orderId === '' ||
            $statusCode === '' ||
            $grossAmount === '' ||
            $signature === '' ||
            $serverKey === ''
        ) {
            return response()->json([
                'message' => 'Data notifikasi tidak lengkap.',
            ], 400);
        }

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json([
                'message' => 'Signature tidak valid.',
            ], 403);
        }

        $payment = Payment::where(
            'gateway_order_id',
            $orderId
        )->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan.',
            ], 404);
        }

        // Pastikan nominal notifikasi sama dengan nominal pembayaran.
        if (
            !is_numeric($grossAmount) ||
            (int) round((float) $grossAmount) !==
            (int) round((float) $payment->amount)
        ) {
            return response()->json([
                'message' => 'Nominal pembayaran tidak sesuai.',
            ], 422);
        }
        try {
            $statusLookupId = $data['transaction_id'] ?? $orderId;

            $verified = $midtrans->getTransactionStatus($statusLookupId);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Status transaksi belum dapat diverifikasi.',
            ], 502);
        }


        if (
            !isset(
            $verified['order_id'],
            $verified['gross_amount'],
            $verified['transaction_status']
        ) ||
            $verified['order_id'] !== $orderId ||
            !is_numeric($verified['gross_amount']) ||
            (int) round((float) $verified['gross_amount']) !==
            (int) round((float) $payment->amount)
        ) {
            return response()->json([
                'message' => 'Verifikasi transaksi gagal.',
            ], 422);
        }

        DB::transaction(function () use ($payment, $verified) {
            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pembayaran yang sudah lunas tidak boleh diturunkan statusnya.
            if ($payment->status === 'paid') {
                return;
            }

            $transactionStatus = $verified['transaction_status'] ?? '';
            $fraudStatus = $verified['fraud_status'] ?? null;
            $verifiedStatusCode = (string) ($verified['status_code'] ?? '');

            $payment->gateway_transaction_id =
                $verified['transaction_id'] ?? $payment->gateway_transaction_id;

            $payment->payment_method =
                $verified['payment_type'] ?? $payment->payment_method;

            if (
                $verifiedStatusCode === '200' &&
                in_array($transactionStatus, ['settlement', 'capture'], true) &&
                (
                    $transactionStatus === 'settlement' ||
                    $fraudStatus === 'accept'
                )
            ) {
                $payment->status = 'paid';
                $payment->paid_at = now();
                $payment->save();

                $payment->order()->update([
                    'payment_status' => 'paid',
                ]);

                return;
            }

            if ($transactionStatus === 'pending') {
                $payment->status = 'pending';
            } elseif (
                in_array(
                    $transactionStatus,
                    ['deny', 'cancel', 'expire', 'failure'],
                    true
                )
            ) {
                $payment->status = 'failed';
            }

            $payment->save();
        });

        return response()->json([
            'message' => 'Notifikasi berhasil diproses.',
        ]);
    }
}
