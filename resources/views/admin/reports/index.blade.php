@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan transaksi dan pembayaran Teaching Factory.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.reports.export.excel', request()->only('date_from', 'date_to')) }}"
                    class="rounded-lg border border-[#25764C] px-4 py-2.5 text-sm font-semibold text-[#25764C] hover:bg-green-50">
                    Ekspor Excel (.xlsx)
                </a>

                <button type="button" onclick="window.print()"
                    class="rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#1e603d]">
                    Cetak Laporan
                </button>
            </div>
        </div>

        {{-- Filter Tanggal --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">

                <div class="flex flex-col gap-1.5">
                    <label for="date_from" class="text-sm font-medium text-gray-700">
                        Dari Tanggal
                    </label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="date_to" class="text-sm font-medium text-gray-700">
                        Sampai Tanggal
                    </label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        min="{{ request('date_from') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>

                <button type="submit"
                    class="rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#1e603d]">
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.reports.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            </form>

            @if ($errors->any())
                <p class="mt-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </p>
            @endif
        </div>


        {{-- Ringkasan --}}
        <div class="flex flex-wrap gap-4">

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Total Pesanan Masuk</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">
                    {{ number_format($totalOrders, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Pesanan Sudah Dibayar</p>
                <p class="mt-2 text-2xl font-bold text-green-600">
                    {{ number_format($purchasedOrders, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Pesanan Belum Lunas</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">
                    {{ number_format($unpaidOrders, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Pesanan dalam Proses</p>
                <p class="mt-2 text-2xl font-bold text-blue-600">
                    {{ number_format($inProgressOrders, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[220px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="mt-2 text-2xl font-bold text-[#25764C]">
                    Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">QC Menunggu</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">
                    {{ number_format($qcPending, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">QC Lulus</p>
                <p class="mt-2 text-2xl font-bold text-green-600">
                    {{ number_format($qcPassed, 0, ',', '.') }}
                </p>
            </div>

            <div class="min-w-[180px] flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">QC Perlu Revisi</p>
                <p class="mt-2 text-2xl font-bold text-red-600">
                    {{ number_format($qcRevision, 0, ',', '.') }}
                </p>
            </div>

        </div>

        {{-- Tabel Laporan Pesanan --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="font-semibold text-gray-800">Laporan Pesanan Masuk</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Menampilkan {{ $orders->firstItem() ?? 0 }}
                    – {{ $orders->lastItem() ?? 0 }}
                    dari {{ $orders->total() }} pesanan.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">No.</th>
                            <th class="px-5 py-3 font-semibold">Nomor Pesanan</th>
                            <th class="px-5 py-3 font-semibold">Pelanggan</th>
                            <th class="px-5 py-3 font-semibold">Produk Dipesan</th>
                            <th class="px-5 py-3 font-semibold">Tanggal</th>
                            <th class="px-5 py-3 font-semibold">Total</th>
                            <th class="px-5 py-3 font-semibold">Status Pesanan</th>
                            <th class="px-5 py-3 font-semibold">Pembayaran</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="align-top hover:bg-gray-50">
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $orders->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-800">
                                    {{ $order->order_number }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $order->user?->name ?? 'Pelanggan tidak tersedia' }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    @forelse ($order->orderItems as $item)
                                        <div class="mb-2 last:mb-0">
                                            <p class="font-medium text-gray-800">
                                                {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $item->quantity }} ×
                                                Rp {{ number_format((float) $item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @empty
                                        <span class="text-gray-400">Tidak ada detail produk</span>
                                    @endforelse
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-800">
                                    Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $orderStatusLabels = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'processing' => 'Diproses',
                                            'completed' => 'Selesai',
                                        ];

                                        $orderStatusColors = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'processing' => 'bg-indigo-100 text-indigo-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                        ];

                                        $currentOrderStatus = $order->status;
                                    @endphp

                                    <span
                                        class="inline-block rounded-full px-2.5 py-1 text-xs font-medium {{ $orderStatusColors[$currentOrderStatus] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $orderStatusLabels[$currentOrderStatus] ?? ucfirst($currentOrderStatus ?? 'Tidak diketahui') }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($order->payment_status === 'paid')
                                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                                    Tidak ada pesanan pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-5 py-4">
                {{ $orders->links() }}
            </div>

        </div>

        {{-- Tabel Laporan Produksi --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="font-semibold text-gray-800">Laporan Produksi</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Rekap produksi berdasarkan tanggal produksi dan guru penanggung jawab.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">No.</th>
                            <th class="px-5 py-3 font-semibold">Produk</th>
                            <th class="px-5 py-3 font-semibold">Guru Penanggung Jawab</th>
                            <th class="px-5 py-3 font-semibold">Jumlah</th>
                            <th class="px-5 py-3 font-semibold">Tanggal Produksi</th>
                            <th class="px-5 py-3 font-semibold">Progres</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold">Catatan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($productions as $production)
                            <tr class="align-top hover:bg-gray-50">
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $productions->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $production->guru?->name ?? 'Guru tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ number_format($production->quantity, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $production->production_date?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td class="min-w-32 px-5 py-4">
                                    <div class="mb-1 text-xs font-medium text-gray-700">
                                        {{ $production->progress }}%
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full bg-[#25764C]"
                                            style="width: {{ min(100, max(0, (int) $production->progress)) }}%">
                                        </div>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $productionLabels = [
                                            'planned' => 'Direncanakan',
                                            'in_progress' => 'Sedang Diproduksi',
                                            'qc' => 'Menunggu QC',
                                            'revision' => 'Revisi',
                                            'completed' => 'Selesai',
                                        ];

                                        $productionColors = [
                                            'planned' => 'bg-gray-100 text-gray-700',
                                            'in_progress' => 'bg-blue-100 text-blue-700',
                                            'qc' => 'bg-amber-100 text-amber-700',
                                            'revision' => 'bg-red-100 text-red-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                        ];

                                        $productionStatus = $production->status;
                                    @endphp

                                    <span
                                        class="inline-block rounded-full px-2.5 py-1 text-xs font-medium {{ $productionColors[$productionStatus] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $productionLabels[$productionStatus] ?? ucfirst($productionStatus ?? 'Tidak diketahui') }}
                                    </span>
                                </td>

                                <td class="max-w-xs px-5 py-4 text-gray-600">
                                    {{ $production->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada data produksi pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-5 py-4">
                {{ $productions->links() }}
            </div>

        </div>

        {{-- Tabel Laporan Quality Control --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="font-semibold text-gray-800">Laporan Quality Control (QC)</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Rekap pemeriksaan kualitas produk hasil produksi.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">No.</th>
                            <th class="px-5 py-3 font-semibold">Produk</th>
                            <th class="px-5 py-3 font-semibold">Guru Produksi</th>
                            <th class="px-5 py-3 font-semibold">Pemeriksa QC</th>
                            <th class="px-5 py-3 font-semibold">Tanggal Pemeriksaan</th>
                            <th class="px-5 py-3 font-semibold">Hasil QC</th>
                            <th class="px-5 py-3 font-semibold">Catatan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($qualityControls as $qc)
                            <tr class="align-top hover:bg-gray-50">
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $qualityControls->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $qc->production?->product?->name ?? 'Produk tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $qc->production?->guru?->name ?? 'Guru tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $qc->checkedBy?->name ?? 'Pemeriksa tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $qc->checked_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $qcLabels = [
                                            'pending' => 'Menunggu',
                                            'passed' => 'Lulus',
                                            'revision' => 'Perlu Revisi',
                                        ];

                                        $qcColors = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'passed' => 'bg-green-100 text-green-700',
                                            'revision' => 'bg-red-100 text-red-700',
                                        ];

                                        $qcStatus = $qc->status;
                                    @endphp

                                    <span
                                        class="inline-block rounded-full px-2.5 py-1 text-xs font-medium {{ $qcColors[$qcStatus] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $qcLabels[$qcStatus] ?? ucfirst($qcStatus ?? 'Tidak diketahui') }}
                                    </span>
                                </td>

                                <td class="max-w-xs px-5 py-4 text-gray-600">
                                    {{ $qc->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada data pemeriksaan QC pada rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-5 py-4">
                {{ $qualityControls->links() }}
            </div>

        </div>

    </div>

    {{-- Sembunyikan sidebar saat mencetak --}}
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 12mm;
            }

            aside,
            header,
            nav,
            form,
            button,
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
                font-size: 10px !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .space-y-6 {
                display: block !important;
            }

            .space-y-6>* {
                margin-top: 0 !important;
                margin-bottom: 16px !important;
            }

            .overflow-hidden,
            .overflow-x-auto {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 9px !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                break-inside: avoid;
            }

            th,
            td {
                padding: 6px !important;
                border: 1px solid #d1d5db !important;
            }

            .rounded-xl {
                border-radius: 0 !important;
            }

            a[href] {
                color: black !important;
                text-decoration: none !important;
            }

            .pagination,
            nav[role="navigation"] {
                display: none !important;
            }
        }
    </style>
@endsection