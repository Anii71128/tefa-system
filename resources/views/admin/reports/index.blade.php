@extends('admin.layouts.app')

@section('content')
@php
    $currency = fn($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');

    $chartData = [
        'sales' => array_map(fn($item) => (float) $item['revenue'], $salesChart),
        'production' => array_map(fn($item) => (float) $item['quantity'], $productionChart),
        'transactions' => array_map(fn($item) => (float) $item['transactions'], $transactionChart),
    ];

    $chartLabels = array_map(fn($item) => $item['label'], $salesChart);

    $topProductColors = [
        'bg-green-100 text-green-700',
        'bg-indigo-100 text-indigo-700',
        'bg-amber-100 text-amber-700',
        'bg-blue-100 text-blue-700',
        'bg-pink-100 text-pink-700',
    ];
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900">Laporan</h1>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-green-700">
                    ANALISIS & PERFORMA TEFA
                </span>
            </div>

            <p class="mt-2 text-sm text-gray-500">
                Analisis dan pantau performa penjualan, produksi, dan transaksi unit Teaching Factory SMK.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5h-2"/>
                    <path d="M6 14h12v8H6z"/>
                </svg>
                Export PDF
            </button>

            <a href="{{ route('admin.reports.export.excel', request()->only('date_from', 'date_to')) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#1e603d]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M4 4h16v16H4zM8 8l8 8m0-8-8 8"/>
                </svg>
                Export Excel (.xlsx)
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- FILTER --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 4h16M7 8v12m10-12v12M4 12h16M4 20h16"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-semibold text-gray-800">Filter Rentang Laporan</h2>
                    <p class="text-xs text-gray-500">
                        Pilih rentang tanggal untuk memperbarui seluruh data laporan.
                    </p>
                </div>
            </div>

            <div class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                <span class="mr-1 inline-block h-2 w-2 rounded-full bg-green-600"></span>
                Periode Aktif:
                {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}
                –
                {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </div>
        </div>

        <form action="{{ route('admin.reports.index') }}" method="GET"
            class="mt-4 grid grid-cols-1 items-end gap-4 md:grid-cols-2 xl:grid-cols-5">

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Periode Presets
                </label>

                <select name="preset" id="preset"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                    <option value="today" {{ $preset === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ $preset === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ $preset === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="year" {{ $preset === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom">Rentang Custom</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Tanggal Mulai
                </label>

                <input type="date" name="date_from" id="date_from"
                    value="{{ $dateFrom }}"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm">
            </div>

            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Tanggal Akhir
                </label>

                <input type="date" name="date_to" id="date_to"
                    value="{{ $dateTo }}"
                    min="{{ $dateFrom }}"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm">
            </div>

            <div>
                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1e603d]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 4h16l-6 7v7l-4 2v-9z"/>
                    </svg>
                    Terapkan
                </button>
            </div>

            <div>
                <a href="{{ route('admin.reports.index') }}"
                    class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- PENJUALAN --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Total Penjualan
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="M3 10h18M7 15h2"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-xl font-bold text-gray-900">
                {{ $currency($totalRevenue) }}
            </p>

            <div class="mt-4 border-t border-gray-100 pt-3">
                <p class="text-xs font-medium text-green-700">
                    {{ number_format($purchasedOrders) }} pesanan lunas
                </p>
            </div>
        </div>

        {{-- PRODUKSI --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Total Produksi
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 21h18M5 21V8l7-5 7 5v13M9 12h6M9 16h6"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-2xl font-bold text-gray-900">
                {{ number_format($totalProduction, 0, ',', '.') }}
                <span class="text-sm font-medium text-gray-500">Unit</span>
            </p>

            <div class="mt-4 border-t border-gray-100 pt-3">
                <p class="text-xs text-indigo-700">
                    Jumlah dari data produksi
                </p>
            </div>
        </div>

        {{-- TRANSAKSI --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Total Transaksi
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="5" y="3" width="14" height="18" rx="2"/>
                        <path d="M8 7h8M8 11h8M8 15h4"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-2xl font-bold text-gray-900">
                {{ number_format($totalOrders, 0, ',', '.') }}
            </p>

            <div class="mt-4 border-t border-gray-100 pt-3">
                <p class="text-xs text-orange-700">
                    {{ number_format($successPercentage, 1, ',', '.') }}% pesanan lunas
                </p>
            </div>
        </div>

        {{-- RATA-RATA --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Rata-rata Transaksi
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="M7 10h10M7 14h4"/>
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-xl font-bold text-gray-900">
                {{ $currency($averageTransaction) }}
            </p>

            <div class="mt-4 border-t border-gray-100 pt-3">
                <p class="text-xs text-blue-700">
                    Nilai per pesanan lunas
                </p>
            </div>
        </div>
    </div>

    {{-- TAB GRAFIK --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="inline-flex rounded-xl border border-gray-200 bg-white p-1 shadow-sm">
            <button type="button" data-chart-tab="sales"
                class="chart-tab rounded-lg bg-[#25764C] px-4 py-2 text-sm font-semibold text-white">
                Penjualan
            </button>

            <button type="button" data-chart-tab="production"
                class="chart-tab rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Produksi
            </button>

            <button type="button" data-chart-tab="transactions"
                class="chart-tab rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Transaksi
            </button>
        </div>

        <div class="flex items-center gap-2 text-xs text-gray-500">
            <span class="font-semibold">Tampilan Data:</span>
            <span class="rounded-lg bg-gray-100 px-3 py-2">Bulanan</span>
        </div>
    </div>

    {{-- GRAFIK + TOP PRODUK --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

        {{-- GRAFIK --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm xl:col-span-2">

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800" id="chart-title">
                        Grafik Tren Penjualan
                    </h2>
                    <p class="mt-1 text-xs text-gray-500" id="chart-description">
                        Realisasi penjualan berdasarkan periode laporan
                    </p>
                </div>

                <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                    <span class="h-2.5 w-2.5 rounded-full bg-green-700"></span>
                    <span id="chart-legend">Realisasi Penjualan (Rp)</span>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <div class="min-w-[500px]">
                    <svg id="report-chart" viewBox="0 0 700 320"
                        class="h-auto w-full" role="img" aria-label="Grafik laporan">
                    </svg>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-green-700 shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 3l3 6 6 1-4.5 4.5L21 21l-9-5-9 5 4.5-6.5L3 10l6-1z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">Total Penjualan Periode</p>
                        <p class="font-bold text-gray-800">
                            {{ $currency($totalRevenue) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-gray-600 shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">Rata-rata Transaksi</p>
                        <p class="font-bold text-gray-800">
                            {{ $currency($averageTransaction) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOP PRODUK --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h2 class="font-bold text-gray-800">Produk Terlaris</h2>
                <span class="text-xs text-gray-400">Periode aktif</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse($topProducts as $product)
                    <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl font-bold
                            {{ $topProductColors[$loop->index] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $loop->iteration }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-800">
                                {{ $product->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ number_format($product->total_quantity, 0, ',', '.') }} terjual
                            </p>
                        </div>

                        <p class="text-xs font-bold text-green-700">
                            {{ $currency($product->total_sales) }}
                        </p>
                    </div>
                @empty
                    <div class="py-10 text-center text-sm text-gray-400">
                        Belum ada penjualan produk pada periode ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RINCIAN PENJUALAN + PANEL --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

        {{-- TABEL --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-2">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Rincian Penjualan per Produk
                    </h2>
                    <p class="mt-1 text-xs text-gray-500">
                        Daftar kontribusi produk terhadap akumulasi penjualan.
                    </p>
                </div>

                <button type="button" onclick="window.print()"
                    class="rounded-lg border border-gray-200 bg-gray-50 p-2 text-gray-600 hover:bg-gray-100"
                    title="Cetak">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5h-2"/>
                        <path d="M6 14h12v8H6z"/>
                    </svg>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Jumlah</th>
                            <th class="px-5 py-4">Harga Satuan</th>
                            <th class="px-5 py-4">Total Penjualan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($salesDetails as $item)
                            @php
                                $unitPrice = $item->total_quantity > 0
                                    ? $item->total_sales / $item->total_quantity
                                    : 0;
                            @endphp

                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-800">
                                    {{ $item->name }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ number_format($item->total_quantity, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $currency($unitPrice) }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-bold text-gray-800">
                                    {{ $currency($item->total_sales) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                    Tidak ada data penjualan produk pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 bg-gray-50 px-5 py-4">
                <p class="text-xs text-gray-500">
                    {{ number_format($salesDetails->count()) }} produk ditampilkan
                </p>

                <div>
                    <p class="text-xs text-gray-500">Total terakumulasi</p>
                    <p class="text-lg font-bold text-green-700">
                        {{ $currency($totalSalesDetails) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- PANEL SAMPING --}}
        <div class="space-y-5">

            {{-- CETAK --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h4"/>
                        </svg>
                    </div>

                    <h2 class="font-bold text-gray-800">Cetak Dokumen Resmi</h2>
                </div>

                <p class="mt-3 text-xs leading-relaxed text-gray-500">
                    Gunakan cetak laporan untuk menyimpan laporan dalam format PDF melalui printer browser.
                </p>

                <button type="button" onclick="window.print()"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-[#25764C] px-4 py-3 text-sm font-semibold text-white hover:bg-[#1e603d]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5h-2"/>
                        <path d="M6 14h12v8H6z"/>
                    </svg>
                    Cetak / Simpan PDF
                </button>
            </div>

            {{-- EXPORT --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h4"/>
                        </svg>
                    </div>

                    <h2 class="font-bold text-gray-800">Export Data</h2>
                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Unduh laporan transaksi pada rentang periode aktif.
                </p>

                <a href="{{ route('admin.reports.export.excel', request()->only('date_from', 'date_to')) }}"
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700 hover:bg-green-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 4h16v16H4zM8 8l8 8m0-8-8 8"/>
                    </svg>
                    Unduh Excel (.xlsx)
                </a>
            </div>

            {{-- RIWAYAT --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>

                    <h2 class="font-bold text-gray-800">Riwayat Ekspor</h2>
                </div>

                <div class="mt-4 rounded-xl bg-gray-50 p-4">
                    <p class="text-sm font-medium text-gray-700">
                        Riwayat ekspor belum tersedia
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-gray-500">
                        Sistem saat ini belum menyimpan histori unduhan. File Excel dapat diunduh melalui tombol ekspor.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- LAPORAN PESANAN --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-800">Rincian Pesanan</h2>
            <p class="mt-1 text-sm text-gray-500">
                Rincian pesanan pada periode laporan.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Nomor Pesanan</th>
                        <th class="px-5 py-4">Pelanggan</th>
                        <th class="px-5 py-4">Produk</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Pembayaran</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        @php
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'processing' => 'Diproses',
                                'completed' => 'Selesai',
                            ];

                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'processing' => 'bg-indigo-100 text-indigo-700',
                                'completed' => 'bg-green-100 text-green-700',
                            ];

                            $status = $order->status;
                        @endphp

                        <tr class="transition hover:bg-gray-50">
                            <td class="px-5 py-4 text-gray-500">
                                {{ $orders->firstItem() + $loop->index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">
                                {{ $order->order_number ?? '#' . $order->id }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $order->user?->name ?? 'Pelanggan tidak tersedia' }}
                            </td>

                            <td class="min-w-[220px] px-5 py-4">
                                @forelse($order->orderItems as $item)
                                    <div class="mb-2 last:mb-0">
                                        <p class="font-medium text-gray-800">
                                            {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $item->quantity }} × {{ $currency($item->price) }}
                                        </p>
                                    </div>
                                @empty
                                    <span class="text-gray-400">Tidak ada detail produk</span>
                                @endforelse
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                {{ $order->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">
                                {{ $currency($order->total_amount) }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$status] ?? ucfirst($status ?? 'Tidak diketahui') }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                @if($order->payment_status === 'paid')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Lunas
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                                Tidak ada pesanan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- LAPORAN PRODUKSI --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-800">Rincian Produksi</h2>
            <p class="mt-1 text-sm text-gray-500">
                Data produksi berdasarkan tanggal dan penanggung jawab.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Produk</th>
                        <th class="px-5 py-4">Guru</th>
                        <th class="px-5 py-4">Jumlah</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Progres</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Catatan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($productions as $production)
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
                            $progress = min(100, max(0, (int) ($production->progress ?? 0)));
                        @endphp

                        <tr class="transition hover:bg-gray-50">
                            <td class="px-5 py-4 text-gray-500">
                                {{ $productions->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $production->guru?->name ?? 'Guru tidak tersedia' }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ number_format((float) ($production->quantity ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                {{ $production->production_date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="min-w-[150px] px-5 py-4">
                                <p class="mb-1 text-xs font-semibold text-gray-700">
                                    {{ $progress }}%
                                </p>

                                <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full bg-[#25764C]"
                                        style="width: {{ $progress }}%">
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $productionColors[$productionStatus] ?? 'bg-gray-100 text-gray-600' }}">
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
                                Belum ada data produksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4">
            {{ $productions->links() }}
        </div>
    </div>

    {{-- LAPORAN QC --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-800">Laporan Quality Control</h2>
            <p class="mt-1 text-sm text-gray-500">
                Rekap pemeriksaan kualitas produk hasil produksi.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Produk</th>
                        <th class="px-5 py-4">Guru Produksi</th>
                        <th class="px-5 py-4">Pemeriksa</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Hasil QC</th>
                        <th class="px-5 py-4">Catatan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($qualityControls as $qc)
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

                        <tr class="transition hover:bg-gray-50">
                            <td class="px-5 py-4 text-gray-500">
                                {{ $qualityControls->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $qc->production?->product?->name ?? 'Produk tidak tersedia' }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $qc->production?->guru?->name ?? 'Guru tidak tersedia' }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $qc->checkedBy?->name ?? 'Pemeriksa tidak tersedia' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                {{ $qc->checked_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $qcColors[$qcStatus] ?? 'bg-gray-100 text-gray-600' }}">
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
                                Belum ada data QC.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-5 py-4">
            {{ $qualityControls->links() }}
        </div>
    </div>
</div>

{{-- GRAFIK --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const datasets = @json($chartData);
        const labels = @json($chartLabels);

        const svg = document.getElementById('report-chart');
        const title = document.getElementById('chart-title');
        const description = document.getElementById('chart-description');
        const legend = document.getElementById('chart-legend');
        const tabs = document.querySelectorAll('.chart-tab');

        const config = {
            sales: {
                title: 'Grafik Tren Penjualan',
                description: 'Realisasi penjualan berdasarkan periode laporan',
                legend: 'Realisasi Penjualan (Rp)',
                format: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value),
            },
            production: {
                title: 'Grafik Tren Produksi',
                description: 'Jumlah produksi berdasarkan periode laporan',
                legend: 'Jumlah Produksi (Unit)',
                format: value => new Intl.NumberFormat('id-ID').format(value) + ' Unit',
            },
            transactions: {
                title: 'Grafik Tren Transaksi',
                description: 'Jumlah transaksi berdasarkan periode laporan',
                legend: 'Jumlah Transaksi',
                format: value => new Intl.NumberFormat('id-ID').format(value) + ' transaksi',
            },
        };

        function drawChart(type) {
            const data = datasets[type] || [];
            const chartConfig = config[type];

            title.textContent = chartConfig.title;
            description.textContent = chartConfig.description;
            legend.textContent = chartConfig.legend;

            const width = 700;
            const height = 320;
            const left = 65;
            const right = 20;
            const top = 25;
            const bottom = 55;

            const plotWidth = width - left - right;
            const plotHeight = height - top - bottom;

            const max = Math.max(...data, 1);
            const min = 0;

            const points = data.map((value, index) => {
                const x = data.length <= 1
                    ? left + plotWidth / 2
                    : left + (index / (data.length - 1)) * plotWidth;

                const y = top + plotHeight - ((value - min) / (max || 1)) * plotHeight;

                return { x, y, value };
            });

            let html = '';

            // Grid
            for (let i = 0; i <= 4; i++) {
                const y = top + (plotHeight / 4) * i;

                html += `
                    <line x1="${left}" y1="${y}" x2="${width-right}" y2="${y}"
                        stroke="#e5e7eb" stroke-dasharray="4 4"/>
                `;
            }

            // Axis labels
            for (let i = 0; i <= 4; i++) {
                const value = max - (max / 4) * i;
                const y = top + (plotHeight / 4) * i + 4;

                html += `
                    <text x="${left-10}" y="${y}" text-anchor="end"
                        fill="#9ca3af" font-size="11">
                        ${type === 'sales'
                            ? new Intl.NumberFormat('id-ID', {
                                notation: 'compact',
                                maximumFractionDigits: 1
                            }).format(value)
                            : Math.round(value)}
                    </text>
                `;
            }

            // Area
            if (points.length > 0) {
                const pointString = points.map(p => `${p.x},${p.y}`).join(' ');
                const areaString = `${left},${top+plotHeight} ${pointString} ${width-right},${top+plotHeight}`;

                html += `
                    <polygon points="${areaString}" fill="#25764C" opacity="0.10"/>
                    <polyline points="${pointString}" fill="none" stroke="#25764C"
                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                `;
            }

            // Points
            points.forEach((point, index) => {
                html += `
                    <circle cx="${point.x}" cy="${point.y}" r="4"
                        fill="#25764C" stroke="white" stroke-width="2">
                        <title>${labels[index]}: ${chartConfig.format(point.value)}</title>
                    </circle>
                `;
            });

            // Labels
            labels.forEach((label, index) => {
                const x = data.length <= 1
                    ? left + plotWidth / 2
                    : left + (index / (data.length - 1)) * plotWidth;

                html += `
                    <text x="${x}" y="${height-20}" text-anchor="middle"
                        fill="#6b7280" font-size="11">
                        ${label}
                    </text>
                `;
            });

            svg.innerHTML = html;
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(item => {
                    item.classList.remove('bg-[#25764C]', 'text-white');
                    item.classList.add('text-gray-600');
                });

                this.classList.add('bg-[#25764C]', 'text-white');
                this.classList.remove('text-gray-600');

                drawChart(this.dataset.chartTab);
            });
        });

        drawChart('sales');
    });

    // Preset filter
    document.getElementById('preset')?.addEventListener('change', function () {
        if (this.value === 'custom') {
            document.getElementById('date_from').focus();
            return;
        }

        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        this.form.submit();
    });
</script>

{{-- PRINT --}}
<style>
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
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
            padding: 5px !important;
            border: 1px solid #ddd !important;
        }

        .rounded-2xl {
            border-radius: 0 !important;
        }

        .shadow-sm {
            box-shadow: none !important;
        }

        .pagination,
        nav[role="navigation"] {
            display: none !important;
        }
    }
</style>
@endsection