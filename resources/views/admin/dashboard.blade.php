@extends('admin.layouts.app')

@section('content')

    <div class="mb-8">
        <p class="mb-1 text-sm text-gray-500">
            Dashboard
        </p>

        <h1 class="text-2xl font-bold text-gray-900">
            Dashboard Admin
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Pantau aktivitas dan operasional Teaching Factory.
        </p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Pesanan
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                0
            </p>

            <p class="mt-2 text-xs text-gray-400">
                Semua pesanan
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Produk
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                0
            </p>

            <p class="mt-2 text-xs text-gray-400">
                Produk dan jasa
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Pendapatan
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                Rp 0
            </p>

            <p class="mt-2 text-xs text-gray-400">
                Dari transaksi
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
                Siswa Aktif
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                0
            </p>

            <p class="mt-2 text-xs text-gray-400">
                Siswa yang terdaftar
            </p>
        </div>

    </div>

    {{-- Area grafik --}}
    <div class="mt-6 flex flex-col gap-6 xl:flex-row">

        <div class="flex-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Statistik Penjualan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan penjualan dalam beberapa periode.
                </p>
            </div>

            <div class="flex h-64 items-center justify-center rounded-lg bg-gray-50">
                <p class="text-sm text-gray-400">
                    Grafik penjualan akan ditampilkan di sini.
                </p>
            </div>

        </div>

        <div class="w-full xl:w-[380px] rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Status Produksi
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan produksi saat ini.
                </p>
            </div>

            <div class="flex h-64 items-center justify-center rounded-lg bg-gray-50">
                <p class="text-sm text-gray-400">
                    Grafik produksi akan ditampilkan di sini.
                </p>
            </div>

        </div>

    </div>

    {{-- Aktivitas --}}
    <div class="mt-6 flex flex-col gap-6 xl:flex-row">

        <div class="flex-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Pesanan Terbaru
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Pesanan terbaru yang masuk ke sistem.
                    </p>
                </div>

                <span class="text-sm text-gray-400">
                    Belum ada data
                </span>
            </div>

            <div class="flex h-40 items-center justify-center rounded-lg bg-gray-50">
                <p class="text-sm text-gray-400">
                    Belum ada pesanan.
                </p>
            </div>

        </div>

        <div class="w-full xl:w-[380px] rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    Pembayaran Pending
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pembayaran yang menunggu verifikasi.
                </p>
            </div>

            <div class="flex h-40 items-center justify-center rounded-lg bg-gray-50">
                <p class="text-sm text-gray-400">
                    Belum ada pembayaran pending.
                </p>
            </div>

        </div>

    </div>

@endsection
