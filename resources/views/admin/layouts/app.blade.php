
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Dashboard - TEFA' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f7f8fa] text-slate-800 antialiased">

@php
    $user = auth()->user();

    $menus = [
        [
            'title' => 'Navigasi Utama',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon' => '▦',
                    'route' => 'admin.dashboard',
                    'active' => 'admin.dashboard',
                ],
                [
                    'label' => 'Produk & Jasa',
                    'icon' => '▤',
                    'route' => 'admin.products.index',
                    'active' => 'admin.products.*',
                ],
                [
                    'label' => 'Pesanan',
                    'icon' => '▧',
                    'route' => 'admin.orders.index',
                    'active' => 'admin.orders.*',
                ],
            ],
        ],
        [
            'title' => 'Operasional',
            'items' => [
                [
                    'label' => 'Produksi',
                    'icon' => '♧',
                    'route' => 'admin.productions.index',
                    'active' => 'admin.productions.*',
                ],
                [
                    'label' => 'Transaksi',
                    'icon' => '▥',
                    'route' => 'admin.transactions.index',
                    'active' => 'admin.transactions.*',
                ],
                [
                    'label' => 'Pembayaran',
                    'icon' => '▣',
                    'route' => 'admin.payments.index',
                    'active' => 'admin.payments.*',
                ],
            ],
        ],
        [
            'title' => 'Manajemen',
            'items' => [
                [
                    'label' => 'Produk Baru',
                    'icon' => '⊞',
                    'route' => 'admin.products.create',
                    'active' => 'admin.products.create',
                ],
                [
                    'label' => 'Laporan',
                    'icon' => '▤',
                    'route' => 'admin.reports.index',
                    'active' => 'admin.reports.*',
                ],
                [
                    'label' => 'Pengaturan',
                    'icon' => '⚙',
                    'route' => 'admin.settings.index',
                    'active' => 'admin.settings.*',
                ],
            ],
        ],
    ];
@endphp

<div class="min-h-screen md:flex">

    {{-- OVERLAY MOBILE --}}
    <div id="sidebarOverlay"
         class="fixed inset-0 z-40 hidden bg-neutral-950/50 md:hidden"
         onclick="closeMobileSidebar()">
    </div>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 h-screen w-64 shrink-0
                  -translate-x-full border-r border-neutral-200 bg-white
                  transition-[width,transform] duration-300 ease-in-out
                  md:sticky md:top-0 md:z-40 md:translate-x-0">

        {{-- TOMBOL COLLAPSE --}}
        <button type="button"
                id="collapseButton"
                onclick="toggleSidebar()"
                title="Tutup sidebar"
                class="absolute -right-3 top-7 z-50 hidden size-6
                       items-center justify-center rounded-full
                       border border-neutral-200 bg-white text-neutral-500
                       shadow-sm transition-colors hover:text-neutral-900 md:flex">

            <span id="collapseIcon"
                  class="inline-block transition-transform duration-300">
                «
            </span>

        </button>

        <div class="flex h-full flex-col overflow-hidden">

            {{-- LOGO --}}
            <div class="px-4 pb-4 pt-5">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex h-10 items-center gap-2.5"
                   title="TEFA Dashboard">

                    <div class="flex size-10 min-w-10 shrink-0 items-center justify-center
                    overflow-hidden rounded-xl bg-white">

                        <img src="{{ asset('logo-tefa.png') }}"
                            alt="Logo TEFA"
                            class="size-full object-contain">

                    </div>

                    <div class="sidebar-label whitespace-nowrap leading-tight
                                transition-opacity duration-300">

                        <p class="text-xl font-extrabold text-neutral-900">
                            TEFA
                        </p>

                        <p class="text-[10px] font-medium uppercase text-neutral-500">
                            SMKN 1 Purwosari
                        </p>

                    </div>

                </a>

            </div>

            <div class="mx-4 border-t border-neutral-200"></div>

            {{-- NAVIGASI --}}
            <nav class="flex-1 overflow-y-auto px-4 pt-5">

                @foreach ($menus as $group)

                    <div class="mb-6">

                        <p class="sidebar-label mb-3 h-4 whitespace-nowrap
                                  px-2 text-[11px] font-semibold uppercase
                                  tracking-wider text-neutral-400
                                  transition-opacity duration-300">

                            {{ $group['title'] }}

                        </p>

                        <ul class="space-y-1">

                            @foreach ($group['items'] as $item)

                                @php
                                    $isActive = request()->routeIs($item['active']);
                                @endphp

                                <li>

                                    <a href="{{ route($item['route']) }}"
                                       title="{{ $item['label'] }}"
                                       onclick="closeMobileSidebar()"
                                       @class([
                                           'nav-link flex h-[42px] w-full items-center gap-3 rounded-xl border px-[9px] text-sm transition-colors duration-200',
                                           'border-emerald-300 bg-emerald-100 font-semibold text-emerald-800 shadow-sm' => $isActive,
                                           'border-transparent bg-transparent text-neutral-600 hover:bg-neutral-100' => !$isActive,
                                       ])>

                                        <span class="nav-icon flex size-5 shrink-0
                                                     items-center justify-center text-base"
                                              @class([
                                                  'text-emerald-700' => $isActive,
                                                  'text-neutral-400' => !$isActive,
                                              ])>

                                            {{ $item['icon'] }}

                                        </span>

                                        <span class="sidebar-label flex-1 whitespace-nowrap
                                                     transition-opacity duration-300">

                                            {{ $item['label'] }}

                                        </span>

                                    </a>

                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endforeach

            </nav>

            {{-- STATUS SHIFT --}}
            <div class="sidebar-label m-4 rounded-xl border border-slate-200
                        bg-slate-50 p-4">

                <div class="flex items-center justify-between">

                    <p class="text-xs font-semibold text-slate-500">
                        STATUS SHIFT
                    </p>

                    <span class="size-2.5 rounded-full bg-emerald-600"></span>

                </div>

                <p class="mt-1 text-sm font-semibold">
                    Shift 1
                    <span class="text-emerald-700">• Aktif</span>
                </p>

            </div>

        </div>

    </aside>

    {{-- KONTEN UTAMA --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 flex h-16 items-center
                       justify-between gap-3 border-b border-neutral-200
                       bg-white px-4 md:px-8">

            {{-- BAGIAN KIRI --}}
            <div class="flex min-w-0 flex-1 items-center gap-3">

                {{-- MOBILE MENU --}}
                <button type="button"
                        onclick="openMobileSidebar()"
                        aria-label="Buka menu"
                        class="shrink-0 text-neutral-600 md:hidden">

                    ☰

                </button>

                {{-- SEARCH --}}
                <form action="{{ route('admin.products.index') }}"
                      method="GET"
                      class="flex h-9 w-full max-w-md items-center gap-2
                             rounded-xl border border-neutral-200
                             bg-neutral-50 px-3">

                    <span class="shrink-0 text-neutral-400">
                        ⌕
                    </span>

                    <input id="searchInput"
                           type="search"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Cari produk atau layanan..."
                           class="w-full bg-transparent text-sm outline-none
                                  placeholder:text-neutral-400">

                    <kbd id="shortcutLabel"
                         class="hidden shrink-0 whitespace-nowrap rounded
                                bg-white px-1.5 py-0.5 font-sans text-[10px]
                                font-medium text-neutral-500 sm:inline-block">

                        Ctrl K

                    </kbd>

                    <button type="submit" class="hidden">
                        Cari
                    </button>

                </form>

            </div>

            {{-- BAGIAN KANAN --}}
            <div class="flex shrink-0 items-center gap-3 sm:gap-4">

                {{-- NOTIFIKASI --}}
                <div class="relative">

                    <button type="button"
                            id="notificationButton"
                            aria-label="Notifikasi"
                            aria-expanded="false"
                            onclick="toggleNotifications()"
                            class="relative text-neutral-500 hover:text-neutral-800">

                        ♧

                        <span class="absolute -right-0.5 -top-0.5
                                     size-2 rounded-full bg-emerald-600">
                        </span>

                    </button>

                    {{-- DROPDOWN --}}
                    <div id="notificationDropdown"
                         class="absolute right-0 top-full z-50 mt-4 hidden
                                w-64 rounded-xl border border-neutral-200
                                bg-white p-3 shadow-xl">

                        <div class="mb-2 border-b border-neutral-100 pb-3">

                            <p class="text-sm font-bold text-neutral-900">
                                Notifikasi
                            </p>

                            <p class="mt-1 text-xs text-neutral-500">
                                Pintasan aktivitas TEFA
                            </p>

                        </div>

                        <a href="{{ route('admin.orders.index') }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-3
                                  text-sm text-neutral-600 hover:bg-neutral-100">

                            <span>▧</span>
                            Pesanan

                        </a>

                        <a href="{{ route('admin.payments.index') }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-3
                                  text-sm text-neutral-600 hover:bg-neutral-100">

                            <span>▣</span>
                            Pembayaran

                        </a>

                        <a href="{{ route('admin.transactions.index') }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-3
                                  text-sm text-neutral-600 hover:bg-neutral-100">

                            <span>▥</span>
                            Transaksi

                        </a>

                    </div>

                </div>

                {{-- PEMISAH --}}
                <span class="hidden text-neutral-300 sm:inline">
                    │
                </span>

               {{-- PROFIL / PENGATURAN ADMIN --}}
                <div class="flex items-center gap-3 border-neutral-200 sm:border-l sm:pl-4">

                    <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center gap-3"
                    title="Pengaturan Admin">

                        <div class="hidden text-right leading-tight sm:block">

                            <p class="text-sm font-semibold text-neutral-900">
                                {{ $user?->name ?? 'Administrator' }}
                            </p>

                            <p class="text-xs text-neutral-500">
                                Admin
                            </p>

                        </div>

                        <div class="relative">

                            <div class="flex size-9 items-center justify-center
                                        rounded-full bg-emerald-100 text-sm
                                        font-bold text-emerald-800">

                                {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}

                            </div>

                            <span class="absolute -bottom-0.5 -right-0.5
                                        size-2.5 rounded-full border-2
                                        border-white bg-emerald-600">
                            </span>

                        </div>

                    </a>

                </div>

                {{-- LOGOUT --}}
                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button type="submit"
                            onclick="return confirm('Apakah kamu yakin ingin keluar?')"
                            class="text-sm font-semibold text-neutral-500
                                transition hover:text-red-600">

                        Keluar

                    </button>

                </form>

        </header>

        {{-- KONTEN HALAMAN --}}
        <main class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">

            {{-- SUCCESS --}}
            @if (session('success'))

                <div class="mb-5 rounded-xl border border-emerald-200
                            bg-emerald-50 px-4 py-3 text-sm text-emerald-800">

                    {{ session('success') }}

                </div>

            @endif

            {{-- ERROR --}}
            @if (session('error'))

                <div class="mb-5 rounded-xl border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-800">

                    {{ session('error') }}

                </div>

            @endif

            {{-- VALIDATION ERROR --}}
            @if ($errors->any())

                <div class="mb-5 rounded-xl border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-800">

                    <p class="mb-2 font-semibold">
                        Terjadi kesalahan:
                    </p>

                    <ul class="list-inside list-disc">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- SLOT DAN CONTENT --}}
            {{ $slot ?? '' }}

            @yield('content')

        </main>

    </div>

</div>

{{-- JAVASCRIPT --}}
<script>

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const collapseIcon = document.getElementById('collapseIcon');

    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');

    const searchInput = document.getElementById('searchInput');
    const shortcutLabel = document.getElementById('shortcutLabel');

    // SIDEBAR MOBILE: BUKA
    function openMobileSidebar() {

        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');

    }

    // SIDEBAR MOBILE: TUTUP
    function closeMobileSidebar() {

        if (window.innerWidth < 768) {

            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');

        }

    }

    // SIDEBAR DESKTOP: COLLAPSE
    function toggleSidebar() {

        if (window.innerWidth < 768) return;

        const collapsed = sidebar.classList.toggle('md:w-18');

        sidebar.classList.toggle('md:w-64', !collapsed);

        document.querySelectorAll('.sidebar-label').forEach(element => {

            element.classList.toggle('md:opacity-0', collapsed);
            element.classList.toggle('md:pointer-events-none', collapsed);

        });

        document.querySelectorAll('.nav-link').forEach(element => {

            element.classList.toggle('md:justify-center', collapsed);

        });

        collapseIcon.classList.toggle('rotate-180', collapsed);

        document.getElementById('collapseButton').title =
            collapsed ? 'Buka sidebar' : 'Tutup sidebar';

        localStorage.setItem(
            'tefa-admin-sidebar',
            collapsed ? 'collapsed' : 'expanded'
        );

    }

    // MEMULIHKAN KONDISI SIDEBAR
    function restoreSidebar() {

        if (
            window.innerWidth >= 768 &&
            localStorage.getItem('tefa-admin-sidebar') === 'collapsed'
        ) {

            toggleSidebar();

        }

    }

    // NOTIFIKASI
    function toggleNotifications() {

        const isHidden = notificationDropdown.classList.contains('hidden');

        notificationDropdown.classList.toggle('hidden');

        notificationButton.setAttribute(
            'aria-expanded',
            isHidden ? 'true' : 'false'
        );

    }

    // MENUTUP NOTIFIKASI KETIKA KLIK DI LUAR
    document.addEventListener('click', function(event) {

        if (
            !notificationDropdown.contains(event.target) &&
            !notificationButton.contains(event.target)
        ) {

            notificationDropdown.classList.add('hidden');

            notificationButton.setAttribute('aria-expanded', 'false');

        }

    });

    // SHORTCUT CTRL + K / COMMAND + K
    function handleSearchShortcut(event) {

        if (
            (event.ctrlKey || event.metaKey) &&
            event.key.toLowerCase() === 'k'
        ) {

            event.preventDefault();

            searchInput.focus();

        }

    }

    // DETEKSI LABEL SHORTCUT
    function setShortcutLabel() {

        const isApple = /Mac|iPhone|iPad/i.test(navigator.userAgent);

        shortcutLabel.textContent = isApple ? '⌘K' : 'Ctrl K';

    }

    document.addEventListener('keydown', handleSearchShortcut);

    // RESPONSIVE
    window.addEventListener('resize', function() {

        if (window.innerWidth >= 768) {

            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');

        } else {

            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');

        }

    });

    // INISIALISASI
    document.addEventListener('DOMContentLoaded', function() {

        restoreSidebar();
        setShortcutLabel();

    });

</script>

</body>
</html>
