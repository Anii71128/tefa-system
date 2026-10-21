<?php

namespace App\Exports\Sheets;

use App\Models\Production;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductionsSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null
    ) {}

    public function query(): Builder
    {
        $query = Production::query()
            ->with(['product', 'guru'])
            ->latest();

        if ($this->dateFrom) {
            $query->whereDate('production_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('production_date', '<=', $this->dateTo);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID Produksi',
            'Nama Produk',
            'Nama Guru',
            'Jumlah Produksi',
            'Tanggal Produksi',
            'Progres (%)',
            'Status Produksi',
            'Catatan',
        ];
    }

    public function map($production): array
    {
        return [
            $production->id,
            $production->product?->name ?? '-',
            $production->guru?->name ?? '-',
            $production->quantity,
            $production->production_date?->format('d-m-Y'),
            $production->progress,
            $production->status,
            $production->notes ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Produksi';
    }
}
