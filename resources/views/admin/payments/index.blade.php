
@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-900">Pembayaran</h1>
                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        Verifikasi Kas & Bukti Bayar
                    </span>
                </div>

                <p class="mt-2 max-w-2xl text-sm text-gray-500">
                    Kelola, periksa bukti transfer, dan pantau pembayaran
                    pesanan Teaching Factory.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v12m0 0l4-4m-4 4l-4-4M5 19h14"/>
                    </svg>
                    Ekspor Laporan
                </button>

                @if (Route::has('admin.payments.create'))
                    <a href="{{ route('admin.payments.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#216346] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#184d35]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Pembayaran
                    </a>
                @endif
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Pembayaran --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="absolute left-0 top-0 h-1 w-full bg-[#216346]"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            Rp {{ number_format((float) ($totalRevenue ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m-5-5h7m0 0l-3-3m3 3l-3 3"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    Total nominal pembayaran
                </div>
            </div>

            {{-- Pending --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="absolute left-0 top-0 h-1 w-full bg-amber-600"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Verifikasi</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ number_format((int) ($pendingPayments ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 text-xs text-amber-700">
                    Perlu ditinjau
                </div>
            </div>

            {{-- Paid --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="absolute left-0 top-0 h-1 w-full bg-green-600"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pembayaran Valid</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ number_format((int) ($paidPayments ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12l3 3 5-6"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 text-xs text-green-700">
                    Pembayaran berhasil
                </div>
            </div>

            {{-- Failed --}}
            <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="absolute left-0 top-0 h-1 w-full bg-red-500"></div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pembayaran Ditolak</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ number_format((int) ($failedPayments ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 9l6 6m0-6l-6 6"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 text-xs text-red-600">
                    Pembayaran tidak valid
                </div>
            </div>

        </div>

        {{-- FILTER --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <form action="{{ route('admin.payments.index') }}" method="GET">
                <div class="flex flex-col gap-4">

                    {{-- Pencarian --}}
                    <div>
                        <label for="search" class="mb-2 block text-sm font-medium text-gray-700">
                            Cari pembayaran
                        </label>

                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>

                            <input id="search" type="text" name="search"
                                value="{{ request('search') }}"
                                placeholder="ID pembayaran, nomor pesanan, nama pelanggan..."
                                class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-green-600 focus:ring-2 focus:ring-green-100">
                        </div>
                    </div>

                    {{-- Filter --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                                Status Pembayaran
                            </label>

                            <select id="status" name="status"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-green-600">
                                <option value="">Semua Status</option>
                                <option value="pending" @selected(request('status') === 'pending')>
                                    Pending
                                </option>
                                <option value="paid" @selected(request('status') === 'paid')>
                                    Berhasil
                                </option>
                                <option value="failed" @selected(request('status') === 'failed')>
                                    Ditolak
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="mb-2 block text-sm font-medium text-gray-700">
                                Dari Tanggal
                            </label>

                            <input id="date_from" type="date" name="date_from"
                                value="{{ request('date_from') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-green-600">
                        </div>

                        <div>
                            <label for="date_to" class="mb-2 block text-sm font-medium text-gray-700">
                                Sampai Tanggal
                            </label>

                            <input id="date_to" type="date" name="date_to"
                                value="{{ request('date_to') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-green-600">
                        </div>

                        <div class="flex items-end gap-3">
                            <button type="submit"
                                class="flex-1 rounded-lg bg-[#216346] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#184d35]">
                                Filter
                            </button>

                            <a href="{{ route('admin.payments.index') }}"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                                Reset
                            </a>
                        </div>

                    </div>
                </div>
            </form>

        </div>

        {{-- TABEL --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-5 py-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            Daftar Pembayaran
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Data pembayaran pelanggan Teaching Factory
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                        <span class="h-2 w-2 rounded-full bg-green-600"></span>
                        Data Database
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-4">ID Pembayaran</th>
                            <th class="px-5 py-4">Pelanggan</th>
                            <th class="px-5 py-4">Pesanan</th>
                            <th class="px-5 py-4">Nominal</th>
                            <th class="px-5 py-4">Metode</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($payments as $payment)

                            @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'paid' => 'bg-green-100 text-green-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                ];

                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'paid' => 'Berhasil',
                                    'failed' => 'Ditolak',
                                ];
                            @endphp

                            <tr class="transition hover:bg-gray-50">

                                {{-- ID --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">
                                        #PAY-{{ str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) }}
                                    </p>

                                    <p class="mt-1 max-w-48 truncate text-xs text-gray-400">
                                        {{ $payment->gateway_order_id ?? '-' }}
                                    </p>
                                </td>

                                {{-- CUSTOMER --}}
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $payment->order?->user?->name ?? 'Pengguna tidak ditemukan' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $payment->order?->user?->email ?? '-' }}
                                    </p>
                                </td>

                                {{-- ORDER --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">
                                        {{ $payment->order?->order_number ?? '-' }}
                                    </p>
                                </td>

                                {{-- AMOUNT --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-semibold text-gray-800">
                                        Rp {{ number_format((float) ($payment->amount ?? 0), 0, ',', '.') }}
                                    </p>
                                </td>

                                {{-- METHOD --}}
                                <td class="px-5 py-4">
                                    <span class="text-gray-700">
                                        {{ $payment->payment_method ?: 'Belum diketahui' }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $statusStyles[$payment->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$payment->status] ?? ucfirst($payment->status ?? 'Unknown') }}
                                    </span>
                                </td>

                                {{-- DATE --}}
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $payment->created_at?->format('d/m/Y') ?? '-' }}

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $payment->created_at?->format('H:i') ?? '' }}
                                    </p>
                                </td>

                                {{-- ACTION --}}
                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    @if (Route::has('admin.payments.show'))
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                            class="inline-flex items-center justify-center rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-100">
                                            Detail
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">
                                            Tidak tersedia
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                            <svg class="h-7 w-7 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7h18M5 7l1 13h12l1-13M9 7V5a3 3 0 016 0v2"/>
                                            </svg>
                                        </div>

                                        <p class="font-semibold text-gray-700">
                                            Belum ada data pembayaran
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Data pembayaran akan muncul ketika transaksi dibuat.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($payments instanceof \Illuminate\Contracts\Pagination\Paginator || $payments instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <div class="border-t border-gray-100 px-5 py-4">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
