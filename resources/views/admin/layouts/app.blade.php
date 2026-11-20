<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard - TEFA' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f8fa] text-slate-800 antialiased">

<div class="min-h-screen lg:flex">

    {{-- SIDEBAR --}}
    <aside class="hidden w-[230px] shrink-0 border-r border-slate-200 bg-white lg:flex lg:flex-col">
        <div class="flex h-[70px] items-center justify-between border-b border-slate-100 px-6">
            <a href="{{ route('admin.dashboard') }}"
               class="text-[22px] font-black tracking-tight text-slate-800">
                TEFA<span class="text-emerald-700">.</span>
            </a>
            <span class="text-xl text-slate-400">«</span>
        </div>

        <nav class="flex-1 space-y-7 overflow-y-auto px-4 py-6 text-sm">
            <div>
                <p class="mb-3 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Navigasi Utama
                </p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 rounded-xl bg-[#dff3e6] px-3 py-3 font-semibold text-emerald-800 ring-1 ring-emerald-700/40">
                        <span>▦</span> Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>▤</span> Produk & Jasa
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>▧</span> Pesanan
                    </a>
                </div>
            </div>

            <div>
                <p class="mb-3 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Operasional
                </p>
                <div class="space-y-1">
                    <a href="{{ route('admin.productions.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>♧</span> Produksi
                    </a>
                    <a href="{{ route('admin.transactions.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>▥</span> Transaksi
                    </a>
                    <a href="{{ route('admin.payments.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>▣</span> Pembayaran
                    </a>
                </div>
            </div>

            <div>
                <p class="mb-3 px-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    Manajemen
                </p>
                <div class="space-y-1">
                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>⊞</span> Produk Baru
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>▤</span> Laporan
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-slate-50">
                        <span>⚙</span> Pengaturan
                    </a>
                </div>
            </div>
        </nav>

        <div class="m-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-500">STATUS SHIFT</p>
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
            </div>
            <p class="mt-1 text-sm font-semibold">Shift 1 <span class="text-emerald-700">• Aktif</span></p>
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-20 flex min-h-[70px] flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-3 lg:px-8">
            <div class="flex min-w-0 flex-1 items-center gap-4">
                <form action="{{ route('admin.orders.index') }}" method="GET"
                      class="flex w-full max-w-md items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5">
                    <span class="text-lg text-slate-300">⌕</span>
                    <input type="search" name="search"
                           placeholder="Cari tugas atau pesanan..."
                           class="w-full border-0 bg-transparent text-sm outline-none focus:ring-0">
                    <span class="hidden rounded border bg-white px-2 py-1 text-[10px] text-slate-400 sm:inline">⌘</span>
                </form>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" aria-label="Notifikasi"
                        class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100">
                    ♧
                    <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-emerald-500"></span>
                </button>
                <span class="hidden text-slate-300 sm:inline">│</span>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-bold">
                            {{ auth()->user()->name ?? 'Administrator' }}
                        </p>
                        <p class="text-xs text-slate-500">Admin TEFA</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 font-bold text-emerald-800">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- HALAMAN --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

    </div>
</div>
</body>
</html>
```