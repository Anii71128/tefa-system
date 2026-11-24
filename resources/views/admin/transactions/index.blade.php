
@extends('admin.layouts.app')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'processing' => 'Diproses',
        'completed' => 'Selesai',
    ];

    $statusClasses = [
        'pending' => 'bg-amber-100 text-amber-700',
        'approved' => 'bg-blue-100 text-blue-700',
        'rejected' => 'bg-red-100 text-red-700',
        'processing' => 'bg-indigo-100 text-indigo-700',
        'completed' => 'bg-emerald-100 text-emerald-700',
    ];
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-700">
                    Dashboard
                </a>
                <span>/</span>
                <span>Operasional</span>
                <span>/</span>
                <span class="font-medium text-emerald-700">Transaksi</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    Transaksi
                </h1>

                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                    Kas & Keuangan TEFA
                </span>
            </div>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-500">
                Kelola, catat, dan pantau seluruh transaksi pembayaran
                produk dan jasa Teaching Factory.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="button"
                onclick="window.print()"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v12m0 0l4-4m-4 4l-4-4M5 19h14"/>
                </svg>
                Export Data
            </button>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1D5E3C]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M12 5v14m7-7H5"/>
                </svg>
                Tambah Transaksi
            </a>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                ✓
            </span>
            {{ session('success') }}
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

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- TOTAL --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="h-1 bg-[#25764C]"></div>

            <div class="p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Total Transaksi
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5h6M9 3v4m6-4v4M5 9h14v11H5z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="mt-3 text-2xl font-bold text-gray-900">
                    {{ number_format((int) $totalTransactions, 0, ',', '.') }}
                </h2>
            </div>

            <div class="flex items-center justify-between bg-gray-50 px-5 py-3">
                <span class="text-xs text-gray-500">
                    Seluruh data transaksi
                </span>

                <span class="rounded-md bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                    Database
                </span>
            </div>
        </div>

        {{-- TOTAL PEMBAYARAN --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="h-1 bg-emerald-500"></div>

            <div class="p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Total Pembayaran
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7h18v13H3zM3 7l3-4h12l3 4M7 12h5"/>
                        </svg>
                    </div>
                </div>

                <h2 class="mt-3 text-xl font-bold text-gray-900">
                    Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}
                </h2>
            </div>

            <div class="flex items-center justify-between bg-gray-50 px-5 py-3">
                <span class="text-xs text-gray-500">
                    Dana pembayaran diterima
                </span>

                <span class="rounded-md bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                    Terverifikasi
                </span>
            </div>
        </div>

        {{-- PENDING --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="h-1 bg-amber-600"></div>

            <div class="p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Pembayaran Pending
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="mt-3 text-2xl font-bold text-gray-900">
                    {{ number_format((int) $unpaidTransactions, 0, ',', '.') }}
                </h2>
            </div>

            <div class="flex items-center justify-between bg-gray-50 px-5 py-3">
                <span class="text-xs text-gray-500">
                    Menunggu pembayaran
                </span>

                <span class="rounded-md bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">
                    Perlu Tindakan
                </span>
            </div>
        </div>

        {{-- SUCCESS --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="h-1 bg-indigo-500"></div>

            <div class="p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Transaksi Berhasil
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="mt-3 text-2xl font-bold text-gray-900">
                    {{ number_format((int) $paidTransactions, 0, ',', '.') }}
                </h2>
            </div>

            <div class="flex items-center justify-between bg-gray-50 px-5 py-3">
                <span class="text-xs text-gray-500">
                    Pembayaran terverifikasi
                </span>

                <span class="rounded-md bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">
                    Lunas
                </span>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

        {{-- TABLE --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm xl:col-span-12">

            {{-- TABLE HEADER --}}
            <div class="border-b border-gray-100 p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            Data Transaksi Keuangan
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Monitoring seluruh mutasi pembayaran pesanan TEFA.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Realtime Database
                        </span>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="border-b border-gray-100 bg-gray-50 p-4">
                <form action="{{ route('admin.transactions.index') }}"
                    method="GET"
                    class="grid grid-cols-1 gap-3 lg:grid-cols-12">

                    {{-- SEARCH --}}
                    <div class="relative lg:col-span-5">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.5-4.5M19 10.5a8.5 8.5 0 11-17 0 8.5 8.5 0 0117 0z"/>
                        </svg>

                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nomor transaksi atau pelanggan..."
                            class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>

                    {{-- STATUS --}}
                    <div class="lg:col-span-3">
                        <select name="status"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-emerald-500">
                            <option value="">Semua Status Pesanan</option>
                            <option value="pending" @selected(request('status') === 'pending')>
                                Menunggu
                            </option>
                            <option value="approved" @selected(request('status') === 'approved')>
                                Disetujui
                            </option>
                            <option value="rejected" @selected(request('status') === 'rejected')>
                                Ditolak
                            </option>
                            <option value="processing" @selected(request('status') === 'processing')>
                                Diproses
                            </option>
                            <option value="completed" @selected(request('status') === 'completed')>
                                Selesai
                            </option>
                        </select>
                    </div>

                    {{-- PAYMENT --}}
                    <div class="lg:col-span-2">
                        <select name="payment_status"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-emerald-500">
                            <option value="">Semua Pembayaran</option>
                            <option value="paid" @selected(request('payment_status') === 'paid')>
                                Lunas
                            </option>
                            <option value="unpaid" @selected(request('payment_status') === 'unpaid')>
                                Belum Lunas
                            </option>
                        </select>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex gap-2 lg:col-span-2">
                        <button type="submit"
                            class="flex-1 rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1D5E3C]">
                            Filter
                        </button>

                        <a href="{{ route('admin.transactions.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">

                    <thead class="bg-indigo-50/70">
                        <tr>
                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                No.
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                ID Transaksi
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Relasi Pesanan
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Pelanggan
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Total Pembayaran
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status Pesanan
                            </th>

                            <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Pembayaran
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($transactions as $transaction)

                            <tr class="transition hover:bg-gray-50">

                                {{-- NUMBER --}}
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $transactions->firstItem() + $loop->index }}
                                </td>

                                {{-- TRANSACTION ID --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-mono text-xs font-semibold text-[#25764C]">
                                        TRX-{{ $transaction->id }}
                                    </span>
                                </td>

                                {{-- ORDER --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <a href="{{ route('admin.orders.show', $transaction) }}"
                                        class="inline-flex items-center gap-1 rounded-md bg-indigo-100 px-2 py-1 font-mono text-xs font-semibold text-indigo-700 transition hover:bg-indigo-200">
                                        #ORD-{{ $transaction->order_number }}
                                    </a>
                                </td>

                                {{-- CUSTOMER --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                                            {{ strtoupper(substr($transaction->user?->name ?? 'U', 0, 2)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-gray-800">
                                                {{ $transaction->user?->name ?? 'Pelanggan tidak tersedia' }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-500">
                                                {{ $transaction->user?->email ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- DATE --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-medium text-gray-700">
                                        {{ $transaction->created_at?->format('d M Y') ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $transaction->created_at?->format('H:i') ?? '-' }} WIB
                                    </p>
                                </td>

                                {{-- TOTAL --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="font-bold text-gray-800">
                                        Rp {{ number_format((float) $transaction->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- ORDER STATUS --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                    </span>
                                </td>

                                {{-- PAYMENT STATUS --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($transaction->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-emerald-200 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center">

                                        <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 7h18v13H3zM3 7l3-4h12l3 4"/>
                                            </svg>
                                        </div>

                                        <p class="font-semibold text-gray-700">
                                            Belum ada transaksi
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Data transaksi akan muncul setelah pesanan tersedia.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($transactions->hasPages())
                <div class="flex flex-col gap-3 border-t border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-gray-500">
                        Menampilkan
                        <span class="font-semibold text-gray-700">
                            {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
                        </span>
                        dari
                        <span class="font-semibold text-gray-700">
                            {{ $transactions->total() }}
                        </span>
                        transaksi
                    </p>

                    <div>
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
