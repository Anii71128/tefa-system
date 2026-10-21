<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Transaction;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    public function createSnapToken(array $payload): string
    {
        return Snap::getSnapToken($payload);
    }
    public function getTransactionStatus(string $orderId): array
    {
        return (array) Transaction::status($orderId);
    }
}
