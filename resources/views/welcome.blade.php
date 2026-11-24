<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TEFA | Teaching Factory Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F7F9F8] font-sans text-gray-800 antialiased">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 backdrop-blur-xl">
        <div class="mx-auto flex h-[76px] max-w-[1440px] items-center justify-between px-5 sm:px-8 lg:px-10">

            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-700 text-white shadow-lg shadow-emerald-200/60">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.5L3 11l9 4.5 9-4.5-9-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 13v4.5c3.3 2.2 6.7 2.2 10 0V13M21 11v6" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-gray-900">
                        TEFA<span class="text-emerald-600">.</span>
                    </h1>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-gray-400">
                        Management System
                    </p>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <span class="hidden text-sm font-medium text-gray-400 sm:block">
                    Teaching Factory Platform
                </span>

                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-200/60 transition hover:-translate-y-0.5 hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4m-4-4l5-5m0 0l-5-5m5 5H3" />
                    </svg>
                    Masuk Sistem
                </a>
            </div>
        </div>
    </nav>


    {{-- MAIN CONTENT --}}
    <main class="mx-auto max-w-[1440px] px-5 py-8 sm:px-8 lg:px-10 lg:py-10">

        {{-- WELCOME HERO --}}
        <section
            class="relative overflow-hidden rounded-[28px] bg-gradient-to-br from-emerald-700 via-green-700 to-green-900 p-7 shadow-xl shadow-emerald-900/10 sm:p-10 lg:p-12">

            {{-- Decorative --}}
            <div class="absolute -right-20 -top-32 h-80 w-80 rounded-full border-[40px] border-white/5"></div>
            <div class="absolute -bottom-40 right-1/3 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-start justify-between gap-10 lg:flex-row lg:items-center">

                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-emerald-50 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        TEACHING FACTORY MANAGEMENT SYSTEM
                    </div>

                    <h2
                        class="mt-7 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                        Selamat Datang di
                        <span class="text-emerald-300">Sistem TEFA</span>
                    </h2>

                    <p class="mt-5 max-w-xl text-sm leading-7 text-emerald-50/85 sm:text-base">
                        Platform terintegrasi untuk membantu pengelolaan Teaching Factory,
                        mulai dari manajemen produk, pesanan, kegiatan produksi,
                        penugasan siswa, hingga evaluasi.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-extrabold text-emerald-700 shadow-lg transition hover:-translate-y-1 hover:bg-emerald-50">
                            Mulai Sekarang
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>

                        <a href="#fitur"
                            class="rounded-xl border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-white/20">
                            Lihat Fitur Sistem
                        </a>
                    </div>
                </div>

                {{-- Hero Illustration --}}
                <div class="hidden w-full max-w-sm lg:block">
                    <div class="rounded-3xl border border-white/20 bg-white/10 p-6 backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-emerald-100">PLATFORM TEFA</p>
                                <h3 class="mt-1 text-lg font-extrabold text-white">Satu Sistem Terintegrasi</h3>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-white">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 13h8V3H3v10zm10 8h8V11h-8v10zm0-18v4h8V3h-8zM3 21h8v-4H3v4z" />
                                </svg>
                            </div>
                        </div>

                        <div class="mt-7 grid grid-cols-2 gap-3">
                            @foreach ([
                                    ['Produk', 'package'],
                                    ['Pesanan', 'shopping-cart'],
                                    ['Produksi', 'factory'],
                                    ['Evaluasi', 'clipboard-check'],
                                ] as $item)
                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 text-white">
                                        @if ($item[1] === 'package')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        @elseif ($item[1] === 'shopping-cart')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l2.4 12.4A2 2 0 009.4 17H19a2 2 0 001.9-1.4L23 9H6" />
                                            </svg>
                                        @elseif ($item[1] === 'factory')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 21V9l6 3V9l6 3V5h6v16H3z" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="mt-3 text-sm font-bold text-white">{{ $item[0] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 rounded-2xl bg-white p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-gray-800">Pengelolaan Terintegrasi</p>
                                    <p class="text-xs text-gray-400">Seluruh aktivitas dalam satu platform</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>


        {{-- SYSTEM OVERVIEW --}}
        <section class="mt-10">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-600">
                        System Overview
                    </p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-gray-900">
                        Mengenal Fitur TEFA
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Modul utama yang tersedia dalam sistem manajemen Teaching Factory.
                    </p>
                </div>

                <span class="text-xs font-medium text-gray-400">
                    6 Modul Utama
                </span>
            </div>

            <div id="fitur" class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">

                @php
                    $features = [
                        [
                            'number' => '01',
                            'title' => 'Manajemen Produk',
                            'description' => 'Mengelola data produk, kategori, harga, stok, dan informasi produk TEFA.',
                            'icon' => 'package',
                        ],
                        [
                            'number' => '02',
                            'title' => 'Manajemen Pesanan',
                            'description' => 'Mengelola pesanan pelanggan serta memantau proses transaksi.',
                            'icon' => 'shopping-cart',
                        ],
                        [
                            'number' => '03',
                            'title' => 'Manajemen Produksi',
                            'description' => 'Mengatur kegiatan produksi dan memantau proses pengerjaan.',
                            'icon' => 'factory',
                        ],
                        [
                            'number' => '04',
                            'title' => 'Penugasan Siswa',
                            'description' => 'Mengelola tugas, tenggat waktu, dan progres pengerjaan siswa.',
                            'icon' => 'users',
                        ],
                        [
                            'number' => '05',
                            'title' => 'Quality Control',
                            'description' => 'Mendukung pemeriksaan dan pencatatan kualitas hasil produksi.',
                            'icon' => 'shield-check',
                        ],
                        [
                            'number' => '06',
                            'title' => 'Evaluasi',
                            'description' => 'Mencatat hasil evaluasi keterampilan, kedisiplinan, dan kualitas kerja siswa.',
                            'icon' => 'chart',
                        ],
                    ];
                @endphp

                @foreach ($features as $feature)
                    <div
                        class="group rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5">

                        <div class="flex items-center justify-between">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">

                                @if ($feature['icon'] === 'package')
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                @elseif ($feature['icon'] === 'shopping-cart')
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l2.4 12.4A2 2 0 009.4 17H19a2 2 0 001.9-1.4L23 9H6" />
                                    </svg>
                                @elseif ($feature['icon'] === 'factory')
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 21V9l6 3V9l6 3V5h6v16H3z" />
                                    </svg>
                                @elseif ($feature['icon'] === 'users')
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H2v-2a4 4 0 017.75-1.38M15 7a3 3 0 11-6 0 3 3 0 016 0zm5 2a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                @elseif ($feature['icon'] === 'shield-check')
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11zm-3-11l2 2 4-4" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3v18h18M7 14l4-4 4 4 6-7" />
                                    </svg>
                                @endif
                            </div>

                            <span class="text-3xl font-extrabold text-gray-100 transition group-hover:text-emerald-100">
                                {{ $feature['number'] }}
                            </span>
                        </div>

                        <h3 class="mt-5 text-base font-extrabold text-gray-900">
                            {{ $feature['title'] }}
                        </h3>

                        <p class="mt-2 text-sm leading-7 text-gray-500">
                            {{ $feature['description'] }}
                        </p>

                        <div class="mt-5 h-1 w-9 rounded-full bg-emerald-500 transition-all group-hover:w-16"></div>
                    </div>
                @endforeach

            </div>
        </section>


        {{-- WORKFLOW --}}
        <section class="mt-12 rounded-3xl border border-gray-100 bg-white p-7 shadow-sm sm:p-9">

            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-600">
                        Workflow
                    </p>
                    <h2 class="mt-2 text-2xl font-extrabold text-gray-900">
                        Alur Pengelolaan TEFA
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Gambaran tahapan aktivitas dalam sistem.
                    </p>
                </div>

                <div
                    class="hidden h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 sm:flex">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 12h16m-6-6l6 6-6 6" />
                    </svg>
                </div>
            </div>

            <div class="mt-9 grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                        ['01', 'Produk & Pesanan', 'Pengelolaan produk dan pencatatan pesanan.'],
                        ['02', 'Proses Produksi', 'Pengaturan produksi dan penugasan siswa.'],
                        ['03', 'Quality Control', 'Pemeriksaan kualitas hasil produksi.'],
                        ['04', 'Evaluasi', 'Penilaian dan pencatatan hasil pekerjaan.'],
                    ] as $step)
                    <div class="relative">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-sm font-extrabold text-white shadow-lg shadow-emerald-100">
                            {{ $step[0] }}
                        </div>

                        <h3 class="mt-4 text-sm font-extrabold text-gray-900">
                            {{ $step[1] }}
                        </h3>

                        <p class="mt-2 text-xs leading-6 text-gray-500">
                            {{ $step[2] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>


        {{-- USER ROLES --}}
        <section class="mt-12">
            <div class="text-center">
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-600">
                    User Access
                </p>

                <h2 class="mt-2 text-2xl font-extrabold text-gray-900">
                    Akses Berdasarkan Peran
                </h2>

                <p class="mx-auto mt-2 max-w-xl text-sm leading-7 text-gray-500">
                    Setiap pengguna memiliki akses dan tanggung jawab
                    sesuai dengan peran masing-masing dalam sistem TEFA.
                </p>
            </div>

            <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                @foreach ([
                        ['Admin', 'Mengelola dan mengawasi keseluruhan sistem.', 'shield-check'],
                        ['Guru', 'Mengelola produk, produksi, pesanan, dan evaluasi.', 'graduation-cap'],
                        ['Siswa', 'Melihat tugas, mengirim progres, dan memantau evaluasi.', 'book-open'],
                        ['User', 'Mengakses layanan dan melakukan pemesanan produk.', 'user'],
                    ] as $role)
                    <div
                        class="rounded-2xl border border-gray-100 bg-white p-5 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div
                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                            @if ($role[2] === 'shield-check')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11zm-3-11l2 2 4-4" />
                                </svg>
                            @elseif ($role[2] === 'graduation-cap')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.5L3 11l9 4.5 9-4.5-9-4.5zM7 13v4.5c3.3 2.2 6.7 2.2 10 0V13" />
                                </svg>
                            @elseif ($role[2] === 'book-open')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 7v14m0-14C7 3 3 5 3 5v14s4-2 9 2m0-14c5-4 9-2 9-2v14s-4-2-9 2" />
                                </svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 21a8 8 0 00-16 0m12-13a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            @endif
                        </div>

                        <h3 class="mt-4 text-sm font-extrabold text-gray-900">
                            {{ $role[0] }}
                        </h3>

                        <p class="mt-2 text-xs leading-6 text-gray-500">
                            {{ $role[1] }}
                        </p>
                    </div>
                @endforeach

            </div>
        </section>


        {{-- LOGIN CTA --}}
        <section
            class="mt-12 overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-700 to-green-800 p-8 shadow-xl shadow-emerald-900/10 sm:p-10">
            <div class="flex flex-col items-center justify-between gap-7 text-center sm:flex-row sm:text-left">
                <div>
                    <h2 class="text-2xl font-extrabold text-white">
                        Siap Menggunakan Sistem TEFA?
                    </h2>
                    <p class="mt-3 max-w-xl text-sm leading-7 text-emerald-100">
                        Silakan masuk menggunakan akun yang telah terdaftar
                        untuk mengakses fitur sesuai hak akses Anda.
                    </p>
                </div>

                <a href="{{ route('login') }}"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-white px-7 py-4 text-sm font-extrabold text-emerald-700 shadow-lg transition hover:-translate-y-1 hover:bg-emerald-50">
                    Masuk Sekarang
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </section>

    </main>


    {{-- FOOTER --}}
    <footer class="mt-12 border-t border-gray-200 bg-white">
        <div
            class="mx-auto flex max-w-[1440px] flex-col items-center justify-between gap-3 px-5 py-7 text-center sm:flex-row sm:px-8 lg:px-10">
            <div>
                <h3 class="text-lg font-extrabold text-gray-900">
                    TEFA<span class="text-emerald-600">.</span>
                </h3>
                <p class="text-xs text-gray-400">
                    Teaching Factory Management System
                </p>
            </div>

            <p class="text-xs text-gray-400">
                © {{ date('Y') }} TEFA Management System. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>