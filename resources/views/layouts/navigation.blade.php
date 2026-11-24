
<nav x-data="{ open: false }" class="relative z-50 border-b border-gray-100/80 bg-white/95 shadow-sm backdrop-blur-xl">
    <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
        <div class="flex h-[76px] items-center justify-between">

            {{-- Logo dan Brand --}}
            <div class="flex shrink-0 items-center">
                <a href="{{ Auth::user()->role === 'siswa' ? route('siswa.dashboard') : (Auth::user()->role === 'guru' ? route('guru.dashboard') : route('dashboard')) }}"
                    class="group flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-700 text-white shadow-lg shadow-emerald-200/60 transition duration-300 group-hover:scale-105">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.5L3 11l9 4.5 9-4.5-9-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 13v4.5c3.3 2.2 6.7 2.2 10 0V13M21 11v6" />
                        </svg>
                    </div>

                    <div class="hidden sm:block">
                        <h1 class="text-lg font-extrabold tracking-tight text-gray-900">
                            TEFA<span class="text-emerald-600">.</span>
                        </h1>
                        <p class="-mt-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-gray-400">
                            Teaching Factory
                        </p>
                    </div>
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden items-center gap-1 rounded-2xl bg-gray-50/90 p-1.5 lg:flex">

                @if (Auth::user()->role === 'siswa')

                    <x-nav-link :href="route('siswa.dashboard')"
                        :active="request()->routeIs('siswa.dashboard')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('siswa.tasks.index')"
                        :active="request()->routeIs('siswa.tasks.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Tugas Produksi
                    </x-nav-link>

                    <x-nav-link :href="route('siswa.progress.index')"
                        :active="request()->routeIs('siswa.progress.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Progres
                    </x-nav-link>

                    <x-nav-link :href="route('siswa.history.index')"
                        :active="request()->routeIs('siswa.history.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Riwayat
                    </x-nav-link>

                    <x-nav-link :href="route('siswa.evaluations.index')"
                        :active="request()->routeIs('siswa.evaluations.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Evaluasi
                    </x-nav-link>

                @elseif (Auth::user()->role === 'guru')

                    <x-nav-link :href="route('guru.dashboard')"
                        :active="request()->routeIs('guru.dashboard')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('guru.products.index')"
                        :active="request()->routeIs('guru.products.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Produk
                    </x-nav-link>

                    <x-nav-link :href="route('guru.orders.index')"
                        :active="request()->routeIs('guru.orders.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Pesanan
                    </x-nav-link>

                    <x-nav-link :href="route('guru.productions.index')"
                        :active="request()->routeIs('guru.productions.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Produksi
                    </x-nav-link>

                    <x-nav-link :href="route('guru.production-students.index')"
                        :active="request()->routeIs('guru.production-students.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Penugasan Siswa
                    </x-nav-link>

                    <x-nav-link :href="route('guru.quality-controls.index')"
                        :active="request()->routeIs('guru.quality-controls.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Quality Control
                    </x-nav-link>

                    <x-nav-link :href="route('guru.evaluations.index')"
                        :active="request()->routeIs('guru.evaluations.*')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Evaluasi
                    </x-nav-link>

                @else

                    <x-nav-link :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="!border-0 !px-4 !py-2.5 !text-sm !font-semibold !rounded-xl">
                        Dashboard
                    </x-nav-link>

                @endif
            </div>

            {{-- User Profile Desktop --}}
            <div class="hidden items-center gap-3 lg:flex">
                <div class="h-8 w-px bg-gray-200"></div>

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="group flex items-center gap-3 rounded-2xl px-3 py-2 transition hover:bg-gray-50 focus:outline-none">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-sm font-bold uppercase text-emerald-700 ring-2 ring-white">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>

                            <div class="text-left">
                                <p class="max-w-[130px] truncate text-sm font-bold text-gray-800">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-[11px] font-medium capitalize text-gray-400">
                                    {{ Auth::user()->role }}
                                </p>
                            </div>

                            <svg class="h-4 w-4 text-gray-400 transition group-hover:text-emerald-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-gray-100 px-4 py-3">
                            <p class="text-xs font-medium text-gray-400">Login sebagai</p>
                            <p class="mt-1 truncate text-sm font-bold text-gray-800">
                                {{ Auth::user()->name }}
                            </p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil Saya
                            </span>
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="flex items-center gap-2 text-red-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile Hamburger --}}
            <div class="flex items-center gap-2 lg:hidden">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-sm font-bold uppercase text-emerald-700">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>

                <button @click="open = !open"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-700 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation --}}
    <div x-show="open" x-transition class="border-t border-gray-100 bg-white px-4 pb-5 pt-3 shadow-lg lg:hidden">
        <div class="mb-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-green-700 p-4 text-white">
            <p class="text-xs font-medium text-emerald-100">Selamat datang,</p>
            <p class="mt-1 truncate text-lg font-bold">{{ Auth::user()->name }}</p>
            <span class="mt-2 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold capitalize">
                {{ Auth::user()->role }}
            </span>
        </div>

        <div class="space-y-1">

            @if (Auth::user()->role === 'siswa')

                <x-responsive-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('siswa.tasks.index')" :active="request()->routeIs('siswa.tasks.*')">
                    Tugas Produksi
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('siswa.progress.index')" :active="request()->routeIs('siswa.progress.*')">
                    Progres
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('siswa.history.index')" :active="request()->routeIs('siswa.history.*')">
                    Riwayat
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('siswa.evaluations.index')" :active="request()->routeIs('siswa.evaluations.*')">
                    Evaluasi
                </x-responsive-nav-link>

            @elseif (Auth::user()->role === 'guru')

                <x-responsive-nav-link :href="route('guru.dashboard')" :active="request()->routeIs('guru.dashboard')">
                    Dashboard
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('guru.products.index')" :active="request()->routeIs('guru.products.*')">
                    Produk
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('guru.orders.index')" :active="request()->routeIs('guru.orders.*')">
                    Pesanan
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('guru.productions.index')" :active="request()->routeIs('guru.productions.*')">
                    Produksi
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('guru.production-students.index')" :active="request()->routeIs('guru.production-students.*')">
                    Penugasan Siswa
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('guru.quality-controls.index')" :active="request()->routeIs('guru.quality-controls.*')">
                    Quality Control
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('guru.evaluations.index')" :active="request()->routeIs('guru.evaluations.*')">
                    Evaluasi
                </x-responsive-nav-link>

            @else

                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-responsive-nav-link>

            @endif
        </div>

        <div class="mt-4 border-t border-gray-100 pt-4">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-gray-600 transition hover:bg-emerald-50 hover:text-emerald-700">
                Profil Saya
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="mt-1 flex w-full items-center rounded-xl px-3 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
