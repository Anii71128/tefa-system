<?php

namespace App\Exports;

use App\Exports\Sheets\OrdersSheet;
use App\Exports\Sheets\PaymentsSheet;
use App\Exports\Sheets\ProductionsSheet;
use App\Exports\Sheets\QualityControlsSheet;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportsExport implements Export, WithMultipleSheets
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null
    ) {
    }

    public function sheets(): array
    {
        return [
            new OrdersSheet($this->dateFrom, $this->dateTo),
            new PaymentsSheet($this->dateFrom, $this->dateTo),
            new ProductionsSheet($this->dateFrom, $this->dateTo),
            new QualityControlsSheet($this->dateFrom, $this->dateTo),
        ];
    }
}
