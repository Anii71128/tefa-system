
@extends('admin.layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\DB;
    use App\Models\Order;

    /*
    |--------------------------------------------------------------------------
    | Deteksi struktur database
    |--------------------------------------------------------------------------
    */

    $hasOrdersTable = Schema::hasTable('orders');

    $orderColumns = $hasOrdersTable
        ? Schema::getColumnListing('orders')
        : [];

    $hasOrderColumn = fn($column) => in_array($column, $orderColumns);

    $hasPaymentStatus = $hasOrderColumn('payment_status');
    $hasOrderStatus = $hasOrderColumn('status');
    $hasTotalAmount = $hasOrderColumn('total_amount');
    $hasOrderNumber = $hasOrderColumn('order_number');
    $hasCreatedAt = $hasOrderColumn('created_at');
    $hasPaymentMethod = $hasOrderColumn('payment_method');

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    $search = request('search');
    $statusFilter = request('status');
    $customerFilter = request('customer');
    $monthFilter = request('month');
    $sort = request('sort', 'latest');

    /*
    |--------------------------------------------------------------------------
    | Query pesanan
    |--------------------------------------------------------------------------
    */

    $query = Order::query();

    $query->with([
        'user',
        'orderItems.product',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($search) {
        $query->where(function ($q) use ($search, $hasOrderNumber) {

            if ($hasOrderNumber) {
                $q->where('order_number', 'like', '%' . $search . '%');
            }

            $q->orWhereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', '%' . $search . '%');
            });

            $q->orWhereHas('orderItems.product', function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Status
    |--------------------------------------------------------------------------
    */

    if ($statusFilter && $hasOrderStatus) {
        $query->where('status', $statusFilter);
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Pelanggan
    |--------------------------------------------------------------------------
    */

    if ($customerFilter) {
        $query->whereHas('user', function ($q) use ($customerFilter) {
            $q->where('name', 'like', '%' . $customerFilter . '%');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Bulan
    |--------------------------------------------------------------------------
    */

    if ($monthFilter && $hasCreatedAt) {
        $query->whereMonth('created_at', substr($monthFilter, 5, 2))
              ->whereYear('created_at', substr($monthFilter, 0, 4));
    }

    /*
    |--------------------------------------------------------------------------
    | Pengurutan
    |--------------------------------------------------------------------------
    */

    if ($sort === 'oldest') {
        $query->orderBy('created_at', 'asc');
    } elseif ($sort === 'highest') {
        $query->orderBy('total_amount', 'desc');
    } elseif ($sort === 'lowest') {
        $query->orderBy('total_amount', 'asc');
    } else {
        $query->orderBy('created_at', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    $orders = $query->paginate(10)->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalOrders = Order::count();

    $pendingOrders = $hasOrderStatus
        ? Order::where('status', 'pending')->count()
        : 0;

    $processingOrders = $hasOrderStatus
        ? Order::where('status', 'processing')->count()
        : 0;

    $completedOrders = $hasOrderStatus
        ? Order::where('status', 'completed')->count()
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Persentase selesai
    |--------------------------------------------------------------------------
    */

    $completionPercentage = $totalOrders > 0
        ? round(($completedOrders / $totalOrders) * 100, 1)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Helper format
    |--------------------------------------------------------------------------
    */

    $formatRupiah = fn($value) =>
        'Rp ' . number_format((float) $value, 0, ',', '.');

    /*
    |--------------------------------------------------------------------------
    | Mapping status
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'processing' => 'Diproses',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $statusClasses = [
        'pending' => 'bg-amber-100 text-amber-800',
        'approved' => 'bg-blue-100 text-blue-700',
        'rejected' => 'bg-red-100 text-red-700',
        'processing' => 'bg-indigo-100 text-indigo-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];

    $paymentLabels = [
        'paid' => 'Lunas',
        'pending' => 'Menunggu Pembayaran',
        'unpaid' => 'Belum Dibayar',
        'failed' => 'Gagal',
        'cancelled' => 'Dibatalkan',
    ];

    $paymentClasses = [
        'paid' => 'bg-green-100 text-green-700',
        'pending' => 'bg-amber-100 text-amber-800',
        'unpaid' => 'bg-amber-100 text-amber-800',
        'failed' => 'bg-red-100 text-red-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];

@endphp


<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

                <a href="{{ route('admin.dashboard') }}"
                   class="transition hover:text-[#206344]">
                    Dashboard
                </a>

                <span>/</span>

                <span class="font-medium text-[#206344]">
                    Pesanan
                </span>

            </div>

            <h1 class="text-2xl font-bold tracking-tight text-[#171D2A]">
                Pesanan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola dan pantau seluruh alur pesanan produk fisik dan jasa kejuruan Teaching Factory secara terpadu.
            </p>

        </div>


        {{-- HEADER ACTION --}}
        <div class="flex flex-wrap gap-2">

            @if(Route::has('admin.reports.export.excel'))

                <a href="{{ route('admin.reports.export.excel') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v12m0 0l-4-4m4 4l4-4M5 17v4h14v-4"/>
                    </svg>

                    Ekspor Data

                </a>

            @endif


            @if(Route::has('admin.orders.create'))

                <a href="{{ route('admin.orders.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-[#206344] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#184E36]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 5v14m-7-7h14"/>
                    </svg>

                    Pesanan Baru

                </a>

            @endif

        </div>

    </div>


    {{-- ALERT --}}
    @if(session('success'))

        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 font-bold">
                ✓
            </div>

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


        {{-- TOTAL --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-[#206344] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Total Pesanan
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($totalOrders) }}
                    </h2>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-[#206344]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 4h12v16H6zM9 8h6M9 12h6M9 16h3"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center justify-between gap-2 rounded-lg bg-green-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Seluruh pesanan
                </p>

                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                    Database
                </span>

            </div>

        </div>


        {{-- MENUNGGU --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-amber-700 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Menunggu
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($pendingOrders) }}
                    </h2>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 7v5l3 2"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center justify-between gap-2 rounded-lg bg-amber-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Perlu ditindaklanjuti
                </p>

                <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">
                    Pending
                </span>

            </div>

        </div>


        {{-- DIPROSES --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-indigo-600 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Diproses
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($processingOrders) }}
                    </h2>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 12h16M12 4l8 8-8 8"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center justify-between gap-2 rounded-lg bg-indigo-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Dalam alur produksi
                </p>

                <span class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">
                    Produksi
                </span>

            </div>

        </div>


        {{-- SELESAI --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-green-700 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Selesai
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($completedOrders) }}
                    </h2>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m8 12 3 3 5-6"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center justify-between gap-2 rounded-lg bg-green-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Pesanan selesai
                </p>

                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                    {{ $completionPercentage }}%
                </span>

            </div>

        </div>

    </div>


    {{-- DAFTAR PESANAN --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">


        {{-- HEADER TABEL --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex flex-wrap items-center gap-2">

                    <h2 class="text-base font-bold text-[#171D2A]">
                        Daftar Pesanan
                    </h2>

                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-semibold text-green-700">

                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>

                        Database Sync

                    </span>

                </div>

                <p class="mt-1 text-xs text-gray-500">
                    Data pesanan pelanggan dan transaksi Teaching Factory.
                </p>

            </div>

        </div>


        {{-- FILTER --}}
        <div class="bg-[#F8F9FF] p-4">

            <form action="{{ route('admin.orders.index') }}"
                  method="GET"
                  class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">


                {{-- SEARCH --}}
                <div class="relative xl:col-span-2">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-4.35-4.35"/>
                    </svg>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari ID pesanan, nama, produk..."
                           class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-[#206344] focus:ring-1 focus:ring-[#206344]">

                </div>


                {{-- STATUS --}}
                <select name="status"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-[#206344]">

                    <option value="">
                        Semua Status
                    </option>

                    @foreach($statusLabels as $statusKey => $statusLabel)
                        <option value="{{ $statusKey }}"
                            {{ request('status') === $statusKey ? 'selected' : '' }}>
                            {{ $statusLabel }}
                        </option>
                    @endforeach

                </select>


                {{-- CUSTOMER --}}
                <input type="text"
                       name="customer"
                       value="{{ request('customer') }}"
                       placeholder="Nama pelanggan..."
                       class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-[#206344]">


                {{-- MONTH --}}
                <input type="month"
                       name="month"
                       value="{{ request('month') }}"
                       class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-[#206344]">


                {{-- SORT --}}
                <select name="sort"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-[#206344]">

                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>
                        Terbaru
                    </option>

                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>
                        Terlama
                    </option>

                    <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>
                        Total Tertinggi
                    </option>

                    <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>
                        Total Terendah
                    </option>

                </select>


                {{-- BUTTON --}}
                <div class="flex flex-wrap gap-2 md:col-span-2 xl:col-span-5">

                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#206344] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#184E36]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-4.35-4.35"/>
                        </svg>

                        Filter

                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                       class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Reset
                    </a>

                </div>

            </form>

        </div>


        {{-- FILTER INFO --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 px-5 py-3 text-xs">

            <span class="font-medium text-gray-500">
                Filter:
            </span>

            @if(request('search'))
                <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-600">
                    Pencarian: {{ request('search') }}
                </span>
            @endif

            @if(request('status'))
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-indigo-700">
                    Status: {{ $statusLabels[request('status')] ?? request('status') }}
                </span>
            @endif

            @if(request('customer'))
                <span class="rounded-full bg-blue-50 px-3 py-1 text-blue-700">
                    Pelanggan: {{ request('customer') }}
                </span>
            @endif

            @if(request('month'))
                <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700">
                    Bulan: {{ request('month') }}
                </span>
            @endif

            @if(request('search') || request('status') || request('customer') || request('month'))
                <a href="{{ route('admin.orders.index') }}"
                   class="font-semibold text-[#206344] hover:underline">
                    Reset Semua
                </a>
            @else
                <span class="text-gray-400">
                    Tidak ada filter
                </span>
            @endif

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="min-w-[1200px] w-full text-left">


                {{-- TABLE HEADER --}}
                <thead>

                    <tr class="bg-[#F8F9FF]">

                        <th class="px-4 py-4 text-center">
                            <input type="checkbox"
                                   class="rounded border-gray-300 text-[#206344] focus:ring-[#206344]">
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            ID Pesanan
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            Pelanggan & Identitas
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            Produk / Jasa TEFA
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            Waktu Transaksi
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            Jumlah
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            Biaya & Status Bayar
                        </th>

                        <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                            Status Produksi
                        </th>

                        <th class="px-3 py-4 text-center text-xs font-bold uppercase tracking-wide text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- TABLE BODY --}}
                <tbody class="divide-y divide-gray-100">

                    @forelse($orders as $order)

                        @php

                            $orderStatus = strtolower((string) ($order->status ?? 'pending'));

                            $paymentStatus = strtolower((string) ($order->payment_status ?? 'pending'));

                            $items = $order->orderItems ?? collect();

                            $itemCount = $items->sum('quantity');

                            $firstItem = $items->first();

                            $firstProduct = $firstItem?->product;

                            $orderNumber = $order->order_number
                                ?? ('ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT));

                            $customerName = $order->user->name ?? 'Pengguna';

                            $customerInitials = collect(
                                explode(' ', trim($customerName))
                            )->filter()->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->join('');

                            $createdDate = $order->created_at ?? null;

                            $amount = (float) ($order->total_amount ?? 0);

                            $orderStatusLabel = $statusLabels[$orderStatus] ?? ucfirst($orderStatus);

                            $orderStatusClass = $statusClasses[$orderStatus] ?? 'bg-gray-100 text-gray-600';

                            $paymentLabel = $paymentLabels[$paymentStatus] ?? ucfirst($paymentStatus);

                            $paymentClass = $paymentClasses[$paymentStatus] ?? 'bg-gray-100 text-gray-600';

                            $productName = $firstProduct->name ?? 'Produk tidak ditemukan';

                            $productDescription = $firstProduct->description ?? '';

                            $productImage = $firstProduct->image ?? null;

                            $imageUrl = $productImage
                                ? asset('storage/' . $productImage)
                                : null;

                        @endphp


                        <tr class="transition hover:bg-gray-50/80">


                            {{-- CHECKBOX --}}
                            <td class="px-4 py-5 text-center">

                                <input type="checkbox"
                                       value="{{ $order->id }}"
                                       class="rounded border-gray-300 text-[#206344] focus:ring-[#206344]">

                            </td>


                            {{-- ID PESANAN --}}
                            <td class="px-3 py-5">

                                <span class="inline-flex whitespace-nowrap rounded-md bg-indigo-50 px-2 py-1.5 text-xs font-bold text-indigo-800">
                                    #{{ $orderNumber }}
                                </span>

                            </td>


                            {{-- CUSTOMER --}}
                            <td class="px-3 py-5">

                                <div class="flex min-w-[180px] items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-green-100 text-xs font-bold text-[#206344]">
                                        {{ $customerInitials ?: 'U' }}
                                    </div>

                                    <div class="min-w-0">

                                        <p class="max-w-[180px] truncate text-sm font-semibold text-[#171D2A]">
                                            {{ $customerName }}
                                        </p>

                                        <p class="mt-1 max-w-[180px] truncate text-xs text-gray-500">
                                            {{ $order->user->email ?? '-' }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- PRODUCT --}}
                            <td class="px-3 py-5">

                                <div class="flex min-w-[230px] items-start gap-3">

                                    @if($imageUrl)

                                        <img src="{{ $imageUrl }}"
                                             alt="{{ $productName }}"
                                             class="h-11 w-11 flex-shrink-0 rounded-lg border border-gray-100 object-cover"
                                             onerror="this.style.display='none'">

                                    @else

                                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-500">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M8 12h8M12 8v8"/>
                                            </svg>

                                        </div>

                                    @endif


                                    <div class="min-w-0">

                                        @if($items->count() > 1)

                                            <p class="text-sm font-semibold text-[#171D2A]">
                                                {{ $productName }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                + {{ $items->count() - 1 }} produk lainnya
                                            </p>

                                        @else

                                            <p class="text-sm font-semibold text-[#171D2A]">
                                                {{ $productName }}
                                            </p>

                                        @endif


                                        <p class="mt-1 max-w-[180px] text-xs text-gray-500">
                                            {{ $productDescription ?: 'Pesanan Teaching Factory' }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- WAKTU --}}
                            <td class="px-3 py-5">

                                <div class="whitespace-nowrap">

                                    @if($createdDate)

                                        <p class="text-xs font-semibold text-[#171D2A]">
                                            {{ \Carbon\Carbon::parse($createdDate)->format('d M Y') }}
                                        </p>

                                        <p class="mt-1 text-[10px] text-gray-500">
                                            {{ \Carbon\Carbon::parse($createdDate)->format('H:i') }} WIB
                                        </p>

                                    @else

                                        <span class="text-xs text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- JUMLAH --}}
                            <td class="px-3 py-5">

                                <span class="whitespace-nowrap text-sm font-semibold text-[#171D2A]">
                                    {{ $itemCount }}
                                </span>

                                <span class="ml-1 text-xs text-gray-500">
                                    unit
                                </span>

                            </td>


                            {{-- BIAYA --}}
                            <td class="px-3 py-5">

                                <div class="min-w-[160px]">

                                    <p class="whitespace-nowrap text-sm font-bold text-[#171D2A]">
                                        {{ $formatRupiah($amount) }}
                                    </p>

                                    <span class="mt-1 inline-flex rounded-full px-2 py-1 text-[10px] font-semibold {{ $paymentClass }}">
                                        {{ $paymentLabel }}
                                    </span>

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-3 py-5">

                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-semibold {{ $orderStatusClass }}">

                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                    {{ $orderStatusLabel }}

                                </span>

                            </td>


                            {{-- AKSI --}}
                            <td class="px-3 py-5 text-center">

                                @if(Route::has('admin.orders.show'))

                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       title="Detail Pesanan"
                                       class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white p-2 text-gray-500 transition hover:border-[#206344] hover:bg-green-50 hover:text-[#206344]">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>

                                    </a>

                                @endif

                            </td>


                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="px-5 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.5">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M8 12h8"/>
                                        </svg>

                                    </div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Belum ada pesanan
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Pesanan akan ditampilkan ketika tersedia di database.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <div class="flex flex-col gap-4 border-t border-gray-100 bg-[#F8F9FF] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs text-gray-500">

                @if($orders->total() > 0)

                    Menampilkan

                    <span class="font-semibold text-gray-700">
                        {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                    </span>

                    dari

                    <span class="font-semibold text-gray-700">
                        {{ number_format($orders->total()) }}
                    </span>

                    pesanan

                @else

                    Tidak ada data pesanan

                @endif

            </p>


            @if($orders->hasPages())

                <div>
                    {{ $orders->onEachSide(1)->links() }}
                </div>

            @endif

        </div>


    </div>

</div>

@endsection
