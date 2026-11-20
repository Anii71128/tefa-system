
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-800">
                    Dashboard Guru
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan aktivitas Teaching Factory.
                </p>
            </div>

            <div class="text-sm text-gray-500">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="mx-auto max-w-7xl space-y-7 px-4 sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-green-800 via-green-700 to-emerald-600 p-7 shadow-lg sm:p-9">
                <div class="relative z-10 max-w-2xl">
                    <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-medium text-green-50">
                        TEFA MANAGEMENT SYSTEM
                    </span>

                    <h3 class="mt-5 text-2xl font-bold text-white sm:text-3xl">
                        Selamat datang,
                        {{ auth()->user()->name }}!
                    </h3>

                    <p class="mt-3 max-w-xl text-sm leading-6 text-green-50 sm:text-base">
                        Kelola kegiatan Teaching Factory, pantau proses produksi,
                        dan evaluasi perkembangan siswa melalui satu dashboard.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('guru.productions.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-green-800 shadow-sm transition hover:bg-green-50">
                            Kelola Produksi
                            <span>→</span>
                        </a>

                        <a href="{{ route('guru.orders.index') }}"
                            class="inline-flex items-center rounded-lg border border-white/30 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">
                            Lihat Pesanan
                        </a>
                    </div>
                </div>

                <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border-[35px] border-white/10"></div>
                <div class="pointer-events-none absolute -bottom-32 right-36 h-64 w-64 rounded-full border-[30px] border-white/10"></div>
            </div>

            {{-- Quick Access --}}
            <div>
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">
                        Akses Cepat
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Pilih menu untuk mengelola aktivitas TEFA.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

                    <a href="{{ route('guru.products.index') }}"
                        class="group rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-gray-800">Produk</p>
                        <p class="mt-1 text-xs text-gray-400">Kelola produk</p>
                    </a>

                    <a href="{{ route('guru.orders.index') }}"
                        class="group rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 transition group-hover:bg-orange-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v13h14V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6m-6 4h6"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-gray-800">Pesanan</p>
                        <p class="mt-1 text-xs text-gray-400">Kelola pesanan</p>
                    </a>

                    <a href="{{ route('guru.productions.index') }}"
                        class="group rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700 transition group-hover:bg-green-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v18m9-9H3m2.5-6.5l13 13m0-13l-13 13"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-gray-800">Produksi</p>
                        <p class="mt-1 text-xs text-gray-400">Kelola produksi</p>
                    </a>

                    <a href="{{ route('guru.production-students.index') }}"
                        class="group rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-600 transition group-hover:bg-purple-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-10a4 4 0 100-8m4 18v-2a4 4 0 00-3-3.87M9 11a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-gray-800">Penugasan</p>
                        <p class="mt-1 text-xs text-gray-400">Kelola siswa</p>
                    </a>

                    <a href="{{ route('guru.quality-controls.index') }}"
                        class="group rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.6-3A11.9 11.9 0 0112 3a11.9 11.9 0 01-8.6 3A12 12 0 003 9c0 5.6 3.8 10.3 9 11.6 5.2-1.3 9-6 9-11.6 0-1-.1-2-.4-3z"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-gray-800">Quality Control</p>
                        <p class="mt-1 text-xs text-gray-400">Kontrol kualitas</p>
                    </a>

                    <a href="{{ route('guru.evaluations.index') }}"
                        class="group rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-pink-50 text-pink-600 transition group-hover:bg-pink-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-sm font-semibold text-gray-800">Evaluasi</p>
                        <p class="mt-1 text-xs text-gray-400">Nilai siswa</p>
                    </a>

                </div>
            </div>

            {{-- Information Section --}}
            <div class="grid gap-6 lg:grid-cols-3">

                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800">
                                Aktivitas Pengelolaan TEFA
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Hal-hal yang dapat dikelola melalui dashboard.
                            </p>
                        </div>
                        <div class="rounded-lg bg-green-50 p-2 text-green-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12h14V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-6 space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <span class="font-bold">01</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Manajemen Pesanan</h4>
                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    Periksa pesanan pelanggan dan lakukan persetujuan atau penolakan pesanan.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-700">
                                <span class="font-bold">02</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Manajemen Produksi</h4>
                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    Pantau proses produksi dan kelola penugasan siswa dalam kegiatan TEFA.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                <span class="font-bold">03</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Kontrol Kualitas dan Evaluasi</h4>
                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    Periksa kualitas hasil produksi dan berikan penilaian terhadap kompetensi siswa.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Informasi Akun</h3>
                            <p class="text-xs text-gray-400">Profil pengguna aktif</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-400">Nama Guru</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ auth()->user()->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="mt-1 break-all text-sm font-medium text-gray-700">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Role Pengguna</p>
                            <span class="mt-2 inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                Guru
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex w-full items-center justify-center rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700">
                            Pengaturan Profil
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
