<?php

namespace App\Exports\Sheets;

use App\Models\QualityControl;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class QualityControlsSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null
    ) {
    }

    public function query(): Builder
    {
        $query = QualityControl::query()
            ->with(['production.product', 'production.guru', 'checkedBy'])
            ->latest();

        if ($this->dateFrom) {
            $query->whereDate('checked_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('checked_at', '<=', $this->dateTo);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID QC',
            'Nama Produk',
            'Nama Guru',
            'Nama Pemeriksa',
            'Tanggal Pemeriksaan',
            'Status QC',
            'Catatan',
        ];
    }

    public function map($qualityControl): array
    {
        return [
            $qualityControl->id,
            $qualityControl->production?->product?->name ?? '-',
            $qualityControl->production?->guru?->name ?? '-',
            $qualityControl->checkedBy?->name ?? '-',
            $qualityControl->checked_at?->format('d-m-Y H:i:s'),
            $qualityControl->status,
            $qualityControl->notes ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Quality Control';
    }
}
