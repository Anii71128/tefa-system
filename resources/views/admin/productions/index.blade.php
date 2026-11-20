
@extends('admin.layouts.app')

@section('content')

@php
    use App\Models\Production;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | Struktur database
    |--------------------------------------------------------------------------
    */

    $tableExists = Schema::hasTable('productions');

    $columns = $tableExists
        ? Schema::getColumnListing('productions')
        : [];

    $hasColumn = fn($column) => in_array($column, $columns);

    $hasStatus = $hasColumn('status');
    $hasProgress = $hasColumn('progress');
    $hasQuantity = $hasColumn('quantity');
    $hasCreatedAt = $hasColumn('created_at');
    $hasProductionDate = $hasColumn('production_date');
    $hasProductId = $hasColumn('product_id');

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    $search = request('search');
    $statusFilter = request('status');
    $monthFilter = request('month');
    $sort = request('sort', 'latest');

    /*
    |--------------------------------------------------------------------------
    | Query Produksi
    |--------------------------------------------------------------------------
    */

    $query = Production::query();

    $query->with(['product', 'guru']);

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($search) {

        $query->where(function ($q) use ($search) {

            $q->whereHas('product', function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', '%' . $search . '%');
            });

            $q->orWhereHas('guru', function ($guruQuery) use ($search) {
                $guruQuery->where('name', 'like', '%' . $search . '%');
            });

            if (Schema::hasTable('production_students')) {

                $q->orWhereHas('productionStudents.student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', '%' . $search . '%');
                });

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Filter Status
    |--------------------------------------------------------------------------
    */

    if ($statusFilter && $hasStatus) {
        $query->where('status', $statusFilter);
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Bulan
    |--------------------------------------------------------------------------
    */

    if ($monthFilter && $hasProductionDate) {

        $query->whereMonth('production_date', substr($monthFilter, 5, 2))
              ->whereYear('production_date', substr($monthFilter, 0, 4));

    }

    /*
    |--------------------------------------------------------------------------
    | Sort
    |--------------------------------------------------------------------------
    */

    if ($sort === 'oldest' && $hasCreatedAt) {

        $query->orderBy('created_at', 'asc');

    } elseif ($sort === 'progress_high' && $hasProgress) {

        $query->orderBy('progress', 'desc');

    } elseif ($sort === 'progress_low' && $hasProgress) {

        $query->orderBy('progress', 'asc');

    } elseif ($hasCreatedAt) {

        $query->orderBy('created_at', 'desc');

    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    $productions = $query->paginate(10)->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Statistik
    |--------------------------------------------------------------------------
    */

    $totalProductions = Production::count();

    $plannedCount = $hasStatus
        ? Production::where('status', 'planned')->count()
        : 0;

    $processingCount = $hasStatus
        ? Production::whereIn('status', ['in_progress', 'revision', 'qc'])->count()
        : 0;

    $completedCount = $hasStatus
        ? Production::where('status', 'completed')->count()
        : 0;

    $averageProgress = $hasProgress
        ? round((float) Production::avg('progress'))
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'planned' => 'Belum Mulai',
        'in_progress' => 'Sedang Proses',
        'qc' => 'Quality Control',
        'revision' => 'Revisi',
        'completed' => 'Selesai',
    ];

    $statusClasses = [
        'planned' => 'bg-amber-100 text-amber-800',
        'in_progress' => 'bg-indigo-100 text-indigo-700',
        'qc' => 'bg-purple-100 text-purple-700',
        'revision' => 'bg-red-100 text-red-700',
        'completed' => 'bg-green-100 text-green-700',
    ];

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    $formatDate = function ($date) {
        if (!$date) return '-';

        try {
            return \Carbon\Carbon::parse($date)->format('d M Y');
        } catch (\Throwable $e) {
            return '-';
        }
    };

    $formatDateTime = function ($date) {
        if (!$date) return '-';

        try {
            return \Carbon\Carbon::parse($date)->format('d M Y, H:i');
        } catch (\Throwable $e) {
            return '-';
        }
    };

    $progressValue = function ($production) {
        return min(100, max(0, (float) ($production->progress ?? 0)));
    };

    $selectedProduction = $productions->first();

    /*
    |--------------------------------------------------------------------------
    | Beban Pengerjaan Berdasarkan Data Produksi
    |--------------------------------------------------------------------------
    */

    $plannedPercentage = $totalProductions > 0
        ? round(($plannedCount / $totalProductions) * 100)
        : 0;

    $processingPercentage = $totalProductions > 0
        ? round(($processingCount / $totalProductions) * 100)
        : 0;

    $completedPercentage = $totalProductions > 0
        ? round(($completedCount / $totalProductions) * 100)
        : 0;

@endphp


<div class="space-y-6">


    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

                <a href="{{ route('admin.dashboard') }}"
                   class="hover:text-[#206344]">
                    Dashboard
                </a>

                <span>/</span>

                <span class="font-medium text-[#206344]">
                    Produksi
                </span>

            </div>

            <div class="flex flex-wrap items-center gap-2">

                <h1 class="text-2xl font-bold tracking-tight text-[#171D2A]">
                    Produksi
                </h1>

                <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                    TEFA WORKSHOP
                </span>

            </div>

            <p class="mt-1 text-sm text-gray-500">
                Kelola dan pantau seluruh alur proses produksi barang dan jasa kejuruan Teaching Factory secara terpadu.
            </p>

        </div>


        {{-- HEADER ACTION --}}
        <div class="flex flex-wrap gap-2">

            @if(Route::has('admin.reports.index'))

                <a href="{{ route('admin.reports.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v12m0 0l-4-4m4 4l4-4M5 17v4h14v-4"/>
                    </svg>

                    Laporan Produksi

                </a>

            @endif


            @if(Route::has('admin.productions.create'))

                <a href="{{ route('admin.productions.create') }}"
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

                    Produksi Baru

                </a>

            @endif

        </div>

    </div>


    {{-- ALERT --}}
    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif

    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            <ul class="list-inside list-disc">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


        {{-- TOTAL --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-[#206344] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Total Produksi
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($totalProductions) }}
                    </h2>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-[#206344]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 8h8M8 12h8M8 16h4"/>
                    </svg>

                </div>

            </div>

            <div class="mt-4 flex items-center justify-between rounded-lg bg-green-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Seluruh batch produksi
                </p>

                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                    Database
                </span>

            </div>

        </div>


        {{-- BELUM MULAI --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-amber-700 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Belum Mulai
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($plannedCount) }}
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

            <div class="mt-4 flex items-center justify-between rounded-lg bg-amber-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Antrean pengerjaan
                </p>

                <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">
                    {{ $plannedPercentage }}%
                </span>

            </div>

        </div>


        {{-- SEDANG PROSES --}}
        <div class="rounded-xl border border-gray-100 border-t-[3px] border-t-indigo-600 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Sedang Proses
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-[#171D2A]">
                        {{ number_format($processingCount) }}
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

            <div class="mt-4 flex items-center justify-between rounded-lg bg-indigo-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Pengerjaan berlangsung
                </p>

                <span class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">
                    {{ $processingPercentage }}%
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
                        {{ number_format($completedCount) }}
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

            <div class="mt-4 flex items-center justify-between rounded-lg bg-green-50 px-3 py-2">

                <p class="text-xs text-gray-600">
                    Rata-rata progres
                </p>

                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                    {{ $averageProgress }}%
                </span>

            </div>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

        <form action="{{ route('admin.productions.index') }}"
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
                       placeholder="Cari produk, guru, siswa..."
                       class="w-full rounded-lg border border-gray-200 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-[#206344] focus:ring-1 focus:ring-[#206344]">

            </div>


            {{-- STATUS --}}
            <select name="status"
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-[#206344]">

                <option value="">
                    Semua Status
                </option>

                @foreach($statusLabels as $key => $label)

                    <option value="{{ $key }}"
                        {{ request('status') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>

                @endforeach

            </select>


            {{-- FILTER BULAN --}}
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

                <option value="progress_high" {{ $sort === 'progress_high' ? 'selected' : '' }}>
                    Progres Tertinggi
                </option>

                <option value="progress_low" {{ $sort === 'progress_low' ? 'selected' : '' }}>
                    Progres Terendah
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

                <a href="{{ route('admin.productions.index') }}"
                   class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


        {{-- TABEL --}}
        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm xl:col-span-2">


            {{-- HEADER TABEL --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">

                <div>

                    <h2 class="text-base font-bold text-[#171D2A]">
                        Daftar Antrean & Pengerjaan
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Data pengerjaan produksi
                    </p>

                </div>

                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                    {{ number_format($totalProductions) }} Batch
                </span>

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="min-w-[1000px] w-full text-left">


                    {{-- HEADER --}}
                    <thead>

                        <tr class="bg-[#F8F9FF]">

                            <th class="px-4 py-4 text-center">
                                <input type="checkbox"
                                       class="rounded border-gray-300 text-[#206344] focus:ring-[#206344]">
                            </th>

                            <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                                Kode Produksi
                            </th>

                            <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                                Pesanan & Penanggung Jawab
                            </th>

                            <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                                Produk / Jasa
                            </th>

                            <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                                Jadwal
                            </th>

                            <th class="px-3 py-4 text-xs font-bold uppercase tracking-wide text-gray-500">
                                Progres
                            </th>

                            <th class="px-3 py-4 text-center text-xs font-bold uppercase tracking-wide text-gray-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    {{-- BODY --}}
                    <tbody class="divide-y divide-gray-100">

                        @forelse($productions as $production)

                            @php

                                $productionStatus = strtolower((string) ($production->status ?? 'planned'));

                                $productionProgress = $progressValue($production);

                                $productionName = $production->product->name ?? 'Produk tidak ditemukan';

                                $guruName = $production->guru->name ?? '-';

                                $productionQuantity = $production->quantity ?? 0;

                                $productionDate = $production->production_date ?? null;

                                $productionCode = 'PROD-' . str_pad($production->id, 5, '0', STR_PAD_LEFT);

                                $productionStatusLabel = $statusLabels[$productionStatus] ?? ucfirst($productionStatus);

                                $productionStatusClass = $statusClasses[$productionStatus] ?? 'bg-gray-100 text-gray-600';

                            @endphp


                            <tr class="transition hover:bg-gray-50">


                                {{-- CHECKBOX --}}
                                <td class="px-4 py-5 text-center">

                                    <input type="checkbox"
                                           value="{{ $production->id }}"
                                           class="rounded border-gray-300 text-[#206344] focus:ring-[#206344]">

                                </td>


                                {{-- KODE --}}
                                <td class="px-3 py-5">

                                    <div class="min-w-[130px]">

                                        <p class="text-sm font-bold text-[#206344]">
                                            #{{ $productionCode }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Batch Produksi
                                        </p>

                                    </div>

                                </td>


                                {{-- RESPONSIBLE --}}
                                <td class="px-3 py-5">

                                    <div class="min-w-[180px]">

                                        <p class="text-sm font-semibold text-[#171D2A]">
                                            {{ $guruName }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Penanggung Jawab
                                        </p>

                                    </div>

                                </td>


                                {{-- PRODUK --}}
                                <td class="px-3 py-5">

                                    <div class="min-w-[180px]">

                                        <p class="text-sm font-semibold text-[#171D2A]">
                                            {{ $productionName }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $productionQuantity }} unit
                                        </p>

                                    </div>

                                </td>


                                {{-- JADWAL --}}
                                <td class="px-3 py-5">

                                    <div class="min-w-[140px]">

                                        <p class="text-xs font-semibold text-[#171D2A]">
                                            Target:
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $formatDate($productionDate) }}
                                        </p>

                                    </div>

                                </td>


                                {{-- PROGRES --}}
                                <td class="px-3 py-5">

                                    <div class="min-w-[120px]">

                                        <div class="mb-2 flex items-center justify-between">

                                            <span class="text-xs font-semibold text-gray-700">
                                                {{ $productionProgress }}%
                                            </span>

                                        </div>

                                        <div class="h-2 overflow-hidden rounded-full bg-gray-200">

                                            <div class="h-full rounded-full bg-[#206344]"
                                                 style="width: {{ $productionProgress }}%">
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- AKSI --}}
                                <td class="px-3 py-5">

                                    <div class="flex items-center justify-center gap-2">

                                        @if(Route::has('admin.productions.show'))

                                            <a href="{{ route('admin.productions.show', $production) }}"
                                               title="Detail"
                                               class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white p-2 text-gray-500 transition hover:bg-green-50 hover:text-[#206344]">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>

                                            </a>

                                        @endif


                                        @if(Route::has('admin.productions.edit'))

                                            <a href="{{ route('admin.productions.edit', $production) }}"
                                               title="Edit"
                                               class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white p-2 text-gray-500 transition hover:bg-amber-50 hover:text-amber-700">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M16 4l4 4-12 12H4v-4L16 4z"/>
                                                </svg>

                                            </a>

                                        @endif

                                    </div>

                                </td>


                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-5 py-16 text-center">

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
                                            Belum ada produksi
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Data produksi akan ditampilkan di sini.
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

                    @if($productions->total() > 0)

                        Menampilkan

                        <span class="font-semibold text-gray-700">
                            {{ $productions->firstItem() }}–{{ $productions->lastItem() }}
                        </span>

                        dari

                        <span class="font-semibold text-gray-700">
                            {{ number_format($productions->total()) }}
                        </span>

                        produksi

                    @else

                        Tidak ada data

                    @endif

                </p>

                @if($productions->hasPages())

                    <div>
                        {{ $productions->onEachSide(1)->links() }}
                    </div>

                @endif

            </div>


        </div>


        {{-- PANEL DETAIL --}}
        <div class="space-y-5">


            {{-- DETAIL --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">


                @if($selectedProduction)

                    @php

                        $detailProgress = $progressValue($selectedProduction);

                        $detailStatus = strtolower((string) ($selectedProduction->status ?? 'planned'));

                        $detailStatusLabel = $statusLabels[$detailStatus] ?? ucfirst($detailStatus);

                        $detailProduct = $selectedProduction->product->name ?? 'Produk tidak ditemukan';

                        $detailGuru = $selectedProduction->guru->name ?? '-';

                    @endphp


                    {{-- HEADER --}}
                    <div class="flex items-start justify-between gap-3">

                        <div>

                            <h2 class="text-lg font-bold text-[#171D2A]">
                                #PROD-{{ str_pad($selectedProduction->id, 5, '0', STR_PAD_LEFT) }}
                            </h2>

                            <p class="mt-1 text-xs text-gray-500">
                                Detail dan pantauan produksi
                            </p>

                        </div>

                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$detailStatus] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $detailStatusLabel }}
                        </span>

                    </div>


                    {{-- DETAIL PRODUK --}}
                    <div class="mt-4 rounded-xl bg-gray-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Produk
                        </p>

                        <p class="mt-2 text-sm font-bold text-[#171D2A]">
                            {{ $detailProduct }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Jumlah: {{ $selectedProduction->quantity ?? 0 }} unit
                        </p>

                    </div>


                    {{-- DETAIL GURU --}}
                    <div class="mt-3 rounded-xl bg-green-50 p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                            Penanggung Jawab
                        </p>

                        <p class="mt-2 text-sm font-semibold text-[#171D2A]">
                            {{ $detailGuru }}
                        </p>

                    </div>


                    {{-- JADWAL --}}
                    <div class="mt-3 grid grid-cols-2 gap-3">

                        <div class="rounded-xl border border-gray-100 p-3">

                            <p class="text-xs text-gray-500">
                                Tanggal Produksi
                            </p>

                            <p class="mt-2 text-sm font-semibold text-[#171D2A]">
                                {{ $formatDate($selectedProduction->production_date ?? null) }}
                            </p>

                        </div>

                        <div class="rounded-xl border border-gray-100 p-3">

                            <p class="text-xs text-gray-500">
                                Status
                            </p>

                            <p class="mt-2 text-sm font-semibold text-[#171D2A]">
                                {{ $detailStatusLabel }}
                            </p>

                        </div>

                    </div>


                    {{-- PROGRES --}}
                    <div class="mt-4 rounded-xl bg-indigo-50 p-4">

                        <div class="mb-2 flex items-center justify-between">

                            <p class="text-sm font-semibold text-[#171D2A]">
                                Progres Produksi
                            </p>

                            <span class="text-sm font-bold text-indigo-700">
                                {{ $detailProgress }}%
                            </span>

                        </div>

                        <div class="h-2.5 overflow-hidden rounded-full bg-indigo-100">

                            <div class="h-full rounded-full bg-indigo-600"
                                 style="width: {{ $detailProgress }}%">
                            </div>

                        </div>

                    </div>


                    {{-- TIMELINE --}}
                    <div class="mt-5">

                        <h3 class="text-sm font-bold text-[#171D2A]">
                            Alur Produksi
                        </h3>

                        <div class="mt-4 space-y-4">


                            {{-- MULAI --}}
                            <div class="flex gap-3">

                                <div class="flex flex-col items-center">

                                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-green-700">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m8 12 3 3 5-6"/>
                                        </svg>

                                    </div>

                                    <div class="h-8 w-px bg-gray-200"></div>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Data Produksi Dibuat
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $formatDateTime($selectedProduction->created_at ?? null) }}
                                    </p>

                                </div>

                            </div>


                            {{-- PROGRESS --}}
                            <div class="flex gap-3">

                                <div class="flex flex-col items-center">

                                    <div class="flex h-7 w-7 items-center justify-center rounded-full {{ $detailProgress > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' }}">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M4 12h16M12 4l8 8-8 8"/>
                                        </svg>

                                    </div>

                                    <div class="h-8 w-px bg-gray-200"></div>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Pengerjaan
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Progres {{ $detailProgress }}%
                                    </p>

                                </div>

                            </div>


                            {{-- QC --}}
                            <div class="flex gap-3">

                                <div class="flex h-7 w-7 items-center justify-center rounded-full {{ $detailStatus === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-4 w-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m8 12 3 3 5-6"/>
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Selesai
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $detailStatus === 'completed' ? 'Produksi telah selesai' : 'Menunggu penyelesaian' }}
                                    </p>

                                </div>

                            </div>


                        </div>

                    </div>


                    {{-- ACTION --}}
                    <div class="mt-5">

                        @if(Route::has('admin.productions.show'))

                            <a href="{{ route('admin.productions.show', $selectedProduction) }}"
                               class="block w-full rounded-lg bg-[#206344] px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-[#184E36]">
                                Lihat Detail Produksi
                            </a>

                        @endif

                    </div>


                @else

                    <div class="py-10 text-center">

                        <p class="font-semibold text-gray-700">
                            Belum ada produksi
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Detail produksi akan muncul di sini.
                        </p>

                    </div>

                @endif


            </div>


            {{-- RINGKASAN --}}
            <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <h2 class="text-sm font-bold text-[#171D2A]">
                        Ringkasan Status
                    </h2>

                    <span class="text-xs text-gray-500">
                        Database
                    </span>

                </div>


                {{-- BELUM MULAI --}}
                <div class="mt-5">

                    <div class="mb-2 flex items-center justify-between">

                        <p class="text-xs font-semibold text-gray-700">
                            Belum Mulai
                        </p>

                        <span class="text-xs font-semibold text-amber-700">
                            {{ $plannedPercentage }}%
                        </span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                        <div class="h-full rounded-full bg-amber-500"
                             style="width: {{ $plannedPercentage }}%">
                        </div>

                    </div>

                </div>


                {{-- PROSES --}}
                <div class="mt-4">

                    <div class="mb-2 flex items-center justify-between">

                        <p class="text-xs font-semibold text-gray-700">
                            Sedang Proses
                        </p>

                        <span class="text-xs font-semibold text-indigo-700">
                            {{ $processingPercentage }}%
                        </span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                        <div class="h-full rounded-full bg-indigo-600"
                             style="width: {{ $processingPercentage }}%">
                        </div>

                    </div>

                </div>


                {{-- SELESAI --}}
                <div class="mt-4">

                    <div class="mb-2 flex items-center justify-between">

                        <p class="text-xs font-semibold text-gray-700">
                            Selesai
                        </p>

                        <span class="text-xs font-semibold text-green-700">
                            {{ $completedPercentage }}%
                        </span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                        <div class="h-full rounded-full bg-green-600"
                             style="width: {{ $completedPercentage }}%">
                        </div>

                    </div>

                </div>


                <div class="mt-5 rounded-lg bg-gray-50 p-3">

                    <p class="text-xs text-gray-500">
                        Total batch produksi
                    </p>

                    <p class="mt-1 text-lg font-bold text-[#171D2A]">
                        {{ number_format($totalProductions) }}
                    </p>

                </div>

            </div>


        </div>


    </div>


    {{-- KANBAN --}}
    <div class="space-y-4">


        {{-- HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-3">

            <div>

                <h2 class="text-lg font-bold text-[#171D2A]">
                    Papan Visual Alur Pengerjaan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Visualisasi status pengerjaan berdasarkan data produksi.
                </p>

            </div>

        </div>


        {{-- COLUMNS --}}
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">


            {{-- BELUM MULAI --}}
            <div class="rounded-xl border border-gray-100 bg-[#F8F9FF] p-4">


                <div class="mb-4 flex items-center justify-between">

                    <div class="flex items-center gap-2">

                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>

                        <h3 class="text-sm font-bold text-[#171D2A]">
                            Belum Mulai
                        </h3>

                    </div>

                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">
                        {{ $plannedCount }}
                    </span>

                </div>


                <div class="space-y-3">

                    @forelse($productions->getCollection()->where('status', 'planned') as $production)

                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                            <div class="flex items-start justify-between gap-2">

                                <span class="text-xs font-bold text-amber-700">
                                    #PROD-{{ str_pad($production->id, 5, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="rounded-md bg-gray-100 px-2 py-1 text-xs text-gray-600">
                                    {{ $production->guru->name ?? '-' }}
                                </span>

                            </div>

                            <h4 class="mt-3 text-sm font-bold text-[#171D2A]">
                                {{ $production->product->name ?? 'Produk' }}
                            </h4>

                            <p class="mt-2 text-xs text-gray-500">
                                Penanggung jawab: {{ $production->guru->name ?? '-' }}
                            </p>

                            <div class="mt-3 flex items-center justify-between">

                                <span class="text-xs text-gray-500">
                                    {{ $production->quantity ?? 0 }} unit
                                </span>

                                <span class="rounded-md bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">
                                    {{ $formatDate($production->production_date ?? null) }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-center">

                            <p class="text-xs text-gray-500">
                                Tidak ada produksi yang belum dimulai.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- PROSES --}}
            <div class="rounded-xl border border-gray-100 bg-[#F8F9FF] p-4">


                <div class="mb-4 flex items-center justify-between">

                    <div class="flex items-center gap-2">

                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-600"></span>

                        <h3 class="text-sm font-bold text-[#171D2A]">
                            Sedang Proses
                        </h3>

                    </div>

                    <span class="rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">
                        {{ $processingCount }}
                    </span>

                </div>


                <div class="space-y-3">

                    @forelse($productions->getCollection()->whereIn('status', ['in_progress', 'revision', 'qc']) as $production)

                        @php
                            $kanbanProgress = $progressValue($production);
                        @endphp

                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                            <div class="flex items-start justify-between gap-2">

                                <span class="text-xs font-bold text-indigo-700">
                                    #PROD-{{ str_pad($production->id, 5, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="rounded-md bg-indigo-50 px-2 py-1 text-xs text-indigo-700">
                                    {{ $production->guru->name ?? '-' }}
                                </span>

                            </div>

                            <h4 class="mt-3 text-sm font-bold text-[#171D2A]">
                                {{ $production->product->name ?? 'Produk' }}
                            </h4>

                            <p class="mt-2 text-xs text-gray-500">
                                {{ $statusLabels[$production->status] ?? ucfirst($production->status) }}
                            </p>


                            <div class="mt-4">

                                <div class="mb-2 flex items-center justify-between">

                                    <span class="text-xs text-gray-500">
                                        Progres
                                    </span>

                                    <span class="text-xs font-bold text-indigo-700">
                                        {{ $kanbanProgress }}%
                                    </span>

                                </div>

                                <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                                    <div class="h-full rounded-full bg-indigo-600"
                                         style="width: {{ $kanbanProgress }}%">
                                    </div>

                                </div>

                            </div>


                            <div class="mt-3 flex items-center justify-between">

                                <span class="text-xs text-gray-500">
                                    {{ $production->quantity ?? 0 }} unit
                                </span>

                                <span class="text-xs font-semibold text-gray-600">
                                    {{ $formatDate($production->production_date ?? null) }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-center">

                            <p class="text-xs text-gray-500">
                                Tidak ada produksi yang sedang berjalan.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- SELESAI --}}
            <div class="rounded-xl border border-gray-100 bg-[#F8F9FF] p-4">


                <div class="mb-4 flex items-center justify-between">

                    <div class="flex items-center gap-2">

                        <span class="h-2.5 w-2.5 rounded-full bg-green-700"></span>

                        <h3 class="text-sm font-bold text-[#171D2A]">
                            Selesai
                        </h3>

                    </div>

                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">
                        {{ $completedCount }}
                    </span>

                </div>


                <div class="space-y-3">

                    @forelse($productions->getCollection()->where('status', 'completed') as $production)

                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                            <div class="flex items-start justify-between gap-2">

                                <span class="text-xs font-bold text-green-700">
                                    #PROD-{{ str_pad($production->id, 5, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="rounded-md bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                    Selesai
                                </span>

                            </div>

                            <h4 class="mt-3 text-sm font-bold text-[#171D2A]">
                                {{ $production->product->name ?? 'Produk' }}
                            </h4>

                            <p class="mt-2 text-xs text-gray-500">
                                Penanggung jawab: {{ $production->guru->name ?? '-' }}
                            </p>

                            <div class="mt-4">

                                <div class="mb-2 flex items-center justify-between">

                                    <span class="text-xs text-gray-500">
                                        Progres
                                    </span>

                                    <span class="text-xs font-bold text-green-700">
                                        {{ $progressValue($production) }}%
                                    </span>

                                </div>

                                <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                                    <div class="h-full rounded-full bg-green-600"
                                         style="width: {{ $progressValue($production) }}%">
                                    </div>

                                </div>

                            </div>

                            <div class="mt-3 flex items-center justify-between">

                                <span class="text-xs text-gray-500">
                                    {{ $production->quantity ?? 0 }} unit
                                </span>

                                <span class="text-xs font-semibold text-gray-600">
                                    {{ $formatDate($production->production_date ?? null) }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-center">

                            <p class="text-xs text-gray-500">
                                Belum ada produksi yang selesai.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>


        </div>

    </div>


</div>

@endsection
