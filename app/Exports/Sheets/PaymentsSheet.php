<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentsSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null,
    ) {
    }

    public function query(): Builder
    {
        $query = Payment::query()
            ->with(['order.user'])
            ->latest();

        if ($this->dateFrom) {
            $query->whereHas('order', function (Builder $orderQuery) {
                if ($this->dateFrom) {
                    $orderQuery->whereDate('created_at', '>=', $this->dateFrom);
                }

                if ($this->dateTo) {
                    $orderQuery->whereDate('created_at', '<=', $this->dateTo);
                }
            });
        } elseif ($this->dateTo) {
            $query->whereHas('order', function (Builder $orderQuery) {
                $orderQuery->whereDate('created_at', '<=', $this->dateTo);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nomor Pesanan',
            'Nama Pelanggan',
            'Gateway',
            'ID Transaksi Gateway',
            'Metode Pembayaran',
            'Nominal',
            'Status Pembayaran',
            'Tanggal Dibayar',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->order?->order_number ?? '-',
            $payment->order?->user?->name ?? '-',
            $payment->gateway ?? '-',
            $payment->gateway_transaction_id ?? '-',
            $payment->payment_method ?? '-',
            $payment->amount,
            $payment->status,
            $payment->paid_at?->format('d-m-Y H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'Pembayaran';
    }
}
