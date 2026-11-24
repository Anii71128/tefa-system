
@extends('admin.layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Request;
    use Illuminate\Pagination\LengthAwarePaginator;

    /*
    |--------------------------------------------------------------------------
    | Deteksi struktur database
    |--------------------------------------------------------------------------
    */

    $productTable = 'products';
    $categoryTable = 'categories';

    $hasProductsTable = Schema::hasTable($productTable);
    $hasCategoriesTable = Schema::hasTable($categoryTable);

    $productColumns = $hasProductsTable
        ? Schema::getColumnListing($productTable)
        : [];

    $categoryColumns = $hasCategoriesTable
        ? Schema::getColumnListing($categoryTable)
        : [];

    $hasColumn = fn($column) => in_array($column, $productColumns);

    $categoryNameColumn = collect(['name', 'category_name', 'nama'])
        ->first(fn($column) => in_array($column, $categoryColumns));

    $categoryIdColumn = $hasColumn('category_id');

    $hasSku = $hasColumn('sku');
    $hasImage = $hasColumn('image');
    $hasStock = $hasColumn('stock');
    $hasStatus = $hasColumn('status');
    $hasPrice = $hasColumn('price');
    $hasDescription = $hasColumn('description');
    $hasType = $hasColumn('type');
    $hasUpdatedAt = $hasColumn('updated_at');

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    $search = request('search');
    $categoryFilter = request('category');
    $statusFilter = request('status');
    $sort = request('sort', 'latest');
    $tab = request('tab', 'products');

    /*
    |--------------------------------------------------------------------------
    | Query produk
    |--------------------------------------------------------------------------
    */

    $query = \App\Models\Product::query();

    if ($search) {
        $query->where(function ($q) use ($search, $productColumns) {
            foreach (['name', 'sku', 'description'] as $column) {
                if (in_array($column, $productColumns)) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            }
        });
    }

    if ($categoryFilter && $categoryIdColumn) {
        $query->where('category_id', $categoryFilter);
    }

    if ($statusFilter && $hasStatus) {
        $query->where('status', $statusFilter);
    }

    if ($hasStock && $statusFilter === 'low_stock') {
        $query->where('stock', '>', 0)
              ->where('stock', '<=', 10);
    }

    if ($hasStock && $statusFilter === 'out_of_stock') {
        $query->where('stock', '<=', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Tab Produk / Jasa
    |--------------------------------------------------------------------------
    */

    if ($hasType) {
        if ($tab === 'services') {
            $query->where(function ($q) {
                $q->where('type', 'like', '%jasa%')
                  ->orWhere('type', 'like', '%service%');
            });
        } else {
            $query->where(function ($q) {
                $q->where('type', 'not like', '%jasa%')
                  ->where('type', 'not like', '%service%');
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    if ($sort === 'name') {
        $query->orderBy('name', 'asc');
    } elseif ($sort === 'price_low') {
        $query->orderBy('price', 'asc');
    } elseif ($sort === 'price_high') {
        $query->orderBy('price', 'desc');
    } elseif ($sort === 'stock') {
        $query->orderBy('stock', 'asc');
    } else {
        if ($hasUpdatedAt) {
            $query->orderBy('updated_at', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    $products = $query->paginate(10)->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Statistik produk
    |--------------------------------------------------------------------------
    */

    $totalProducts = $hasProductsTable
        ? \App\Models\Product::count()
        : 0;

    $activeProducts = $hasStatus
        ? \App\Models\Product::where('status', 'active')->count()
        : $totalProducts;

    $lowStockProducts = $hasStock
        ? \App\Models\Product::where('stock', '>', 0)
            ->where('stock', '<=', 10)
            ->count()
        : 0;

    $outOfStockProducts = $hasStock
        ? \App\Models\Product::where('stock', '<=', 0)->count()
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Statistik berdasarkan filter tab
    |--------------------------------------------------------------------------
    */

    $totalServices = 0;

    if ($hasType) {
        $totalServices = \App\Models\Product::where(function ($q) {
            $q->where('type', 'like', '%jasa%')
              ->orWhere('type', 'like', '%service%');
        })->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Data kategori
    |--------------------------------------------------------------------------
    */

    $categories = collect();

    if ($hasCategoriesTable && $categoryNameColumn) {
        $categories = DB::table('categories')
            ->when(
                in_array('status', $categoryColumns),
                fn($q) => $q->where('status', 'active')
            )
            ->orderBy($categoryNameColumn)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    $formatRupiah = fn($value) =>
        'Rp ' . number_format((float) $value, 0, ',', '.');

    $getCategoryName = function ($product) use (
        $hasCategoriesTable,
        $categoryNameColumn,
        $categoryIdColumn
    ) {
        if (
            $hasCategoriesTable &&
            $categoryNameColumn &&
            $categoryIdColumn &&
            $product->category_id
        ) {
            return DB::table('categories')
                ->where('id', $product->category_id)
                ->value($categoryNameColumn) ?? 'Tanpa Kategori';
        }

        return 'Tanpa Kategori';
    };

@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}"
                   class="hover:text-[#206344]">
                    Dashboard
                </a>

                <span>/</span>

                <span class="font-medium text-[#206344]">
                    Produk & Jasa
                </span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-[#171D2A]">
                Produk & Jasa
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola produk dan jasa kejuruan yang tersedia di Teaching Factory SMK.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            @if(Route::has('admin.services.create'))
                <a href="{{ route('admin.services.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-[#206344] shadow-sm transition hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8v8m-4-4h8M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>

                    Tambah Jasa
                </a>
            @endif

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#206344] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#184E36]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Tambah Produk
            </a>

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

        {{-- Total --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-100 border-t-[3px] border-t-[#206344] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Total Produk
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-[#206344]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 7h16M4 7l1 13h14l1-13M9 7V5a3 3 0 016 0v2"/>
                    </svg>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-[#171D2A]">
                    {{ number_format($totalProducts) }}
                </h2>

                <span class="rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-700">
                    SKU Aktif
                </span>
            </div>

            <p class="mt-2 text-xs text-gray-500">
                Katalog aktif unit TeFa SMK
            </p>

        </div>


        {{-- TERSEDIA --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-100 border-t-[3px] border-t-[#206344] bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Produk Tersedia
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 12l4 4L19 6"/>
                    </svg>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-[#171D2A]">
                    {{ number_format($activeProducts) }}
                </h2>

                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                    Aktif
                </span>
            </div>

            <p class="mt-2 text-xs text-gray-500">
                Produk berstatus aktif
            </p>

        </div>


        {{-- STOK MENIPIS --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-100 border-t-[3px] border-t-amber-700 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Stok Menipis
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10 3h4l8 18H2L10 3z"/>
                    </svg>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-amber-700">
                    {{ number_format($lowStockProducts) }}
                </h2>

                <span class="rounded-full bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">
                    Butuh Produksi
                </span>
            </div>

            <p class="mt-2 text-xs text-gray-500">
                Produk dengan stok 1–10
            </p>

        </div>


        {{-- HABIS --}}
        <div class="relative overflow-hidden rounded-xl border border-gray-100 border-t-[3px] border-t-red-600 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-gray-500">
                    Produk Habis
                </p>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <h2 class="text-2xl font-bold text-red-600">
                    {{ number_format($outOfStockProducts) }}
                </h2>

                <span class="rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-700">
                    Segera Restock
                </span>
            </div>

            <p class="mt-2 text-xs text-gray-500">
                Kapasitas produksi penuh/kosong
            </p>

        </div>

    </div>


    {{-- KATALOG --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        {{-- TAB --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-[#F8F9FF] px-4 py-3">

            <div class="flex items-center gap-2">

                <a href="{{ route('admin.products.index', array_merge(request()->except('tab'), ['tab' => 'products'])) }}"
                   class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition
                   {{ $tab !== 'services' ? 'border border-gray-200 bg-white text-[#206344] shadow-sm' : 'text-gray-500 hover:bg-white' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 7h16M4 7l1 13h14l1-13M9 7V5a3 3 0 016 0v2"/>
                    </svg>

                    Produk

                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs">
                        {{ number_format($totalProducts) }}
                    </span>
                </a>


                <a href="{{ route('admin.products.index', array_merge(request()->except('tab'), ['tab' => 'services'])) }}"
                   class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition
                   {{ $tab === 'services' ? 'border border-gray-200 bg-white text-[#206344] shadow-sm' : 'text-gray-500 hover:bg-white' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14 7l3 3-3 3M3 10h14M7 17l-3-3 3-3"/>
                    </svg>

                    Jasa Kejuruan

                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs">
                        {{ number_format($totalServices) }}
                    </span>

                </a>

            </div>

            <div class="flex items-center gap-2 text-sm">

                @if(Route::has('admin.products.index'))
                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-gray-500 transition hover:bg-white hover:text-[#206344]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3v5h5M3.5 8A9 9 0 1021 12"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 7v5l3 2"/>
                        </svg>

                        Reset Filter
                    </a>
                @endif

            </div>

        </div>


        {{-- FILTER --}}
        <div class="bg-[#F8F9FF] px-4 pb-4">

            <form action="{{ route('admin.products.index') }}"
                  method="GET"
                  class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">

                <input type="hidden" name="tab" value="{{ $tab }}">

                {{-- Search --}}
                <div class="relative">

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
                           placeholder="Cari nama produk, SKU..."
                           class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-[#206344] focus:ring-1 focus:ring-[#206344]">

                </div>


                {{-- KATEGORI --}}
                <select name="category"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-[#206344]">

                    <option value="">
                        Semua Konsentrasi / Kategori
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->{$categoryNameColumn} }}
                        </option>
                    @endforeach

                </select>


                {{-- STATUS --}}
                <select name="status"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-[#206344]">

                    <option value="">
                        Semua Status Stok
                    </option>

                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>

                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>
                        Stok Menipis
                    </option>

                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>
                        Stok Habis
                    </option>

                </select>


                {{-- SORT --}}
                <select name="sort"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-[#206344]">

                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>
                        Urutkan: Terbaru
                    </option>

                    <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>
                        Urutkan: Nama
                    </option>

                    <option value="price_low" {{ $sort === 'price_low' ? 'selected' : '' }}>
                        Urutkan: Harga Terendah
                    </option>

                    <option value="price_high" {{ $sort === 'price_high' ? 'selected' : '' }}>
                        Urutkan: Harga Tertinggi
                    </option>

                    <option value="stock" {{ $sort === 'stock' ? 'selected' : '' }}>
                        Urutkan: Stok
                    </option>

                </select>


                <div class="flex flex-wrap gap-2 md:col-span-2 xl:col-span-4">

                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#206344] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#184E36]">

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

                    <a href="{{ route('admin.products.index', ['tab' => $tab]) }}"
                       class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-500 transition hover:bg-gray-50">
                        Reset
                    </a>

                </div>

            </form>

        </div>


        {{-- LABEL FILTER --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 px-5 py-3 text-xs">

            <span class="font-medium text-gray-500">
                Filter Terpasang:
            </span>

            @if(request('search'))
                <span class="rounded-full bg-gray-100 px-3 py-1 font-medium text-gray-600">
                    Pencarian: {{ request('search') }}
                </span>
            @endif

            @if(request('category'))
                <span class="rounded-full bg-indigo-50 px-3 py-1 font-medium text-indigo-700">
                    Kategori: {{ $categories->firstWhere('id', request('category'))?->{$categoryNameColumn} ?? request('category') }}
                </span>
            @endif

            @if(request('status'))
                <span class="rounded-full bg-green-100 px-3 py-1 font-medium text-green-700">
                    Status: {{ request('status') }}
                </span>
            @endif

            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('admin.products.index', ['tab' => $tab]) }}"
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

            <table class="min-w-[1100px] w-full">

                <thead>
                    <tr class="bg-[#F8F9FF]">

                        <th class="w-12 px-4 py-4 text-center">
                            <input type="checkbox"
                                   class="rounded border-gray-300 text-[#206344] focus:ring-[#206344]">
                        </th>

                        <th class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            No
                        </th>

                        <th class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Produk & Identitas SKU
                        </th>

                        <th class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Konsentrasi Kejuruan
                        </th>

                        <th class="px-3 py-4 text-right text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Harga Satuan
                        </th>

                        <th class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Stok Tersedia
                        </th>

                        <th class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Status
                        </th>

                        <th class="px-3 py-4 text-left text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Terakhir Diubah
                        </th>

                        <th class="px-3 py-4 text-center text-[11px] font-bold uppercase tracking-wide text-gray-500">
                            Aksi
                        </th>

                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($products as $product)

                        @php
                            $stock = (int) ($product->stock ?? 0);

                            $productStatus = $product->status ?? 'active';

                            $categoryName = $getCategoryName($product);

                            $updatedDate = $product->updated_at ?? null;

                            if ($stock <= 0) {
                                $stockLabel = 'Habis';
                                $stockClass = 'text-red-600';
                                $statusLabel = 'Habis';
                                $statusClass = 'bg-red-100 text-red-700';
                                $dotClass = 'bg-red-500';
                            } elseif ($stock <= 10) {
                                $stockLabel = 'Tersisa ' . $stock . ' unit';
                                $stockClass = 'text-amber-700';
                                $statusLabel = 'Stok Menipis';
                                $statusClass = 'bg-amber-100 text-amber-800';
                                $dotClass = 'bg-amber-500';
                            } elseif ($productStatus === 'inactive') {
                                $stockLabel = $stock . ' unit';
                                $stockClass = 'text-gray-500';
                                $statusLabel = 'Tidak Aktif';
                                $statusClass = 'bg-gray-100 text-gray-600';
                                $dotClass = 'bg-gray-400';
                            } else {
                                $stockLabel = $stock . ' unit';
                                $stockClass = 'text-[#206344]';
                                $statusLabel = 'Tersedia';
                                $statusClass = 'bg-green-100 text-green-700';
                                $dotClass = 'bg-green-600';
                            }

                            $productImage = $product->image ?? null;

                            $imageUrl = $productImage
                                ? asset('storage/' . $productImage)
                                : null;
                        @endphp


                        <tr class="transition hover:bg-gray-50/80">

                            {{-- CHECKBOX --}}
                            <td class="px-4 py-5 text-center">
                                <input type="checkbox"
                                       value="{{ $product->id }}"
                                       class="rounded border-gray-300 text-[#206344] focus:ring-[#206344]">
                            </td>


                            {{-- NOMOR --}}
                            <td class="px-3 py-5 text-sm text-gray-500">
                                {{ $products->firstItem() + $loop->index }}
                            </td>


                            {{-- PRODUK --}}
                            <td class="px-3 py-5">

                                <div class="flex min-w-[220px] items-center gap-3">

                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}"
                                             alt="{{ $product->name }}"
                                             class="h-11 w-11 flex-shrink-0 rounded-lg border border-gray-100 object-cover"
                                             onerror="this.style.display='none'">
                                    @else
                                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M8 14l2-2 4 4 3-3 4 4"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="min-w-0">

                                        <p class="max-w-[220px] text-sm font-semibold leading-5 text-[#171D2A]">
                                            {{ $product->name ?? 'Tanpa Nama' }}
                                        </p>

                                        <p class="mt-1 text-[10px] font-semibold uppercase tracking-wide text-gray-500">
                                            SKU:
                                            {{ $product->sku ?? ('PROD-' . $product->id) }}
                                        </p>

                                        @if($stock <= 10)
                                            <p class="mt-1 text-[10px] font-medium {{ $stock <= 0 ? 'text-red-600' : 'text-amber-700' }}">
                                                {{ $stock <= 0 ? 'Barang Kosong' : 'Tersisa ' . $stock . ' unit' }}
                                            </p>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- KATEGORI --}}
                            <td class="px-3 py-5">

                                <span class="inline-flex max-w-[150px] items-center justify-center rounded-lg bg-indigo-50 px-3 py-2 text-center text-[11px] font-semibold leading-4 text-indigo-700">
                                    {{ $categoryName }}
                                </span>

                            </td>


                            {{-- HARGA --}}
                            <td class="px-3 py-5 text-right">

                                <span class="text-sm font-semibold text-[#171D2A]">
                                    {{ $formatRupiah($product->price ?? 0) }}
                                </span>

                            </td>


                            {{-- STOK --}}
                            <td class="px-3 py-5">

                                <div class="min-w-[100px]">

                                    <p class="text-sm font-semibold {{ $stockClass }}">
                                        {{ $stockLabel }}
                                    </p>

                                    <div class="mt-2 h-1.5 w-[68px] overflow-hidden rounded-full bg-indigo-100">

                                        @php
                                            $stockPercent = min(100, max(0, $stock * 2));
                                        @endphp

                                        <div class="h-full rounded-full
                                            {{ $stock <= 0 ? 'bg-red-500' : ($stock <= 10 ? 'bg-amber-600' : 'bg-[#206344]') }}"
                                             style="width: {{ $stockPercent }}%">
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-3 py-5">

                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1.5 text-[11px] font-semibold {{ $statusClass }}">

                                    <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>

                                    {{ $statusLabel }}

                                </span>

                            </td>


                            {{-- UPDATED --}}
                            <td class="px-3 py-5">

                                <div class="text-xs font-semibold text-gray-500">

                                    @if($updatedDate)
                                        {{ \Carbon\Carbon::parse($updatedDate)->format('d M Y') }}

                                        <p class="mt-1 text-[10px] font-normal text-gray-400">
                                            {{ \Carbon\Carbon::parse($updatedDate)->format('H:i') }} WIB
                                        </p>
                                    @else
                                        -
                                    @endif

                                </div>

                            </td>


                            {{-- AKSI --}}
                            <td class="px-3 py-5">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- DETAIL --}}
                                    @if(Route::has('admin.products.show'))
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           title="Detail"
                                           class="rounded-lg p-2 text-[#206344] transition hover:bg-green-50">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M4 7h16M4 7l1 13h14l1-13M9 7V5a3 3 0 016 0v2"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M9 12h6"/>
                                            </svg>

                                        </a>
                                    @endif


                                    {{-- EDIT --}}
                                    @if(Route::has('admin.products.edit'))
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           title="Edit"
                                           class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-800">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15 5l4 4M4 20l4-1 11-11-4-4L4 15v5z"/>
                                            </svg>

                                        </a>
                                    @endif


                                    {{-- HAPUS --}}
                                    @if(Route::has('admin.products.destroy'))
                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    title="Hapus"
                                                    class="rounded-lg p-2 text-gray-500 transition hover:bg-red-50 hover:text-red-600">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/>
                                                </svg>

                                            </button>

                                        </form>
                                    @endif

                                </div>

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
                                        Tidak ada produk ditemukan
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Coba ubah filter atau tambahkan produk baru.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER PAGINATION --}}
        <div class="flex flex-col gap-4 border-t border-gray-100 bg-[#F8F9FF] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs text-gray-500">

                @if($products->total() > 0)
                    Menampilkan
                    <span class="font-semibold text-gray-700">
                        {{ $products->firstItem() }}–{{ $products->lastItem() }}
                    </span>

                    dari

                    <span class="font-semibold text-gray-700">
                        {{ number_format($products->total()) }}
                    </span>

                    produk
                @else
                    Tidak ada data produk
                @endif

            </p>

            @if($products->hasPages())
                <div>
                    {{ $products->onEachSide(1)->links() }}
                </div>
            @endif

        </div>

    </div>

</div>

@endsection
