<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrdersSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null
    ) {
    }

    public function query(): Builder
    {
        $query = Order::query()
            ->with(['user', 'orderItems.product'])
            ->latest();

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nomor Pesanan',
            'Nama Pelanggan',
            'Produk',
            'Jumlah Item',
            'Total Pesanan',
            'Status Pesanan',
            'Status Pembayaran',
            'Tanggal Pesanan',
        ];
    }

    public function map($order): array
    {
        $products = $order->orderItems
            ->map(fn($item) => $item->product?->name ?? 'Produk tidak tersedia')
            ->implode(', ');

        return [
            $order->order_number,
            $order->user?->name ?? 'Pelanggan tidak tersedia',
            $products ?: '-',
            $order->orderItems->sum('quantity'),
            $order->total_amount,
            $order->status,
            $order->payment_status,
            $order->created_at?->format('d-m-Y H:i:s'),
        ];
    }

    public function title(): string
    {
        return 'Pesanan';
    }
}
