<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Admin - TEFA' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 shrink-0 border-r border-gray-200 bg-white">

            {{-- Logo --}}
            <div class="flex h-20 items-center border-b border-gray-200 px-6">
                <div>
                    <h1 class="text-xl font-bold text-[#25764C]">
                        TEFA
                    </h1>

                    <p class="text-xs text-gray-500">
                        Admin Panel
                    </p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="p-4">

                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Menu
                </p>

                <div class="space-y-1">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 rounded-lg bg-[#EAF6EF] px-3 py-2.5 text-sm font-medium text-[#25764C]">
                        <span>▣</span>
                        <span>Dashboard</span>
                    </a>

                    {{-- Produk --}}
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Produk / Jasa</span>
                    </a>

                    {{-- Pesanan --}}
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Pesanan</span>
                    </a>

                    {{-- Produksi --}}
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Produksi</span>
                    </a>

                    {{-- Transaksi --}}
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Transaksi</span>
                    </a>

                    {{-- Pembayaran --}}
                    <a href="#"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Pembayaran</span>
                    </a>

                </div>

                {{-- Laporan --}}
                <div class="mt-8">
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Laporan
                    </p>

                    <a href="#"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Laporan</span>
                    </a>
                </div>

                {{-- Lainnya --}}
                <div class="mt-8">
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Lainnya
                    </p>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        <span>□</span>
                        <span>Pengaturan</span>
                    </a>
                </div>

            </nav>
        </aside>

        {{-- Main --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Topbar --}}
            <header class="flex h-20 items-center justify-between border-b border-gray-200 bg-white px-8">

                <div>
                    <p class="text-sm text-gray-500">
                        Sistem Manajemen Teaching Factory
                    </p>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Admin Panel
                    </h2>
                </div>

                <div class="flex items-center gap-4">

                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-800">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Administrator
                        </p>
                    </div>

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#EAF6EF] font-semibold text-[#25764C]">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                </div>

            </header>

            {{-- Content --}}
            <main class="flex-1 p-8">

                {{ $slot ?? '' }}

                @yield('content')

            </main>

        </div>

    </div>

</body>

</html>
