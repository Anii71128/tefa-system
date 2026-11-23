
<x-guest-layout>
    <div class="min-h-screen bg-gray-50 lg:flex">

        {{-- LEFT SIDE: BRANDING --}}
        <div class="relative hidden overflow-hidden bg-gradient-to-br from-emerald-700 via-green-700 to-green-900 lg:flex lg:w-1/2 lg:flex-col lg:justify-between lg:p-12 xl:p-16">

            {{-- Decorative Background --}}
            <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-white/5"></div>
            <div class="absolute right-20 top-1/2 h-64 w-64 rounded-full border border-white/10"></div>

            {{-- Logo --}}
            <div class="relative z-10 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-emerald-700 shadow-lg">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.5L3 11l9 4.5 9-4.5-9-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 13v4.5c3.3 2.2 6.7 2.2 10 0V13M21 11v6" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-white">
                        TEFA<span class="text-emerald-300">.</span>
                    </h1>
                    <p class="text-xs font-medium uppercase tracking-[0.2em] text-emerald-100">
                        Teaching Factory
                    </p>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="relative z-10 max-w-xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-emerald-50 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                    Sistem Manajemen TEFA
                </span>

                <h2 class="mt-8 text-4xl font-extrabold leading-tight text-white xl:text-5xl">
                    Kelola Produksi,
                    <span class="text-emerald-300">Tingkatkan</span>
                    Kreativitas.
                </h2>

                <p class="mt-6 max-w-md text-base leading-8 text-emerald-50/80">
                    Platform digital untuk mengelola aktivitas Teaching Factory,
                    mulai dari produk, pesanan, produksi, hingga evaluasi siswa.
                </p>

                <div class="mt-10 flex flex-wrap gap-3">
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur">
                        <p class="text-2xl font-bold text-white">01</p>
                        <p class="mt-1 text-xs text-emerald-100">Manajemen Produk</p>
                    </div>

                    <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur">
                        <p class="text-2xl font-bold text-white">02</p>
                        <p class="mt-1 text-xs text-emerald-100">Monitoring Produksi</p>
                    </div>

                    <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur">
                        <p class="text-2xl font-bold text-white">03</p>
                        <p class="mt-1 text-xs text-emerald-100">Evaluasi Siswa</p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="relative z-10 border-t border-white/15 pt-6">
                <p class="text-xs text-emerald-100/70">
                    © {{ date('Y') }} TEFA Management System. All rights reserved.
                </p>
            </div>
        </div>

        {{-- RIGHT SIDE: LOGIN FORM --}}
        <div class="flex min-h-screen w-full items-center justify-center px-5 py-12 lg:w-1/2 lg:px-12">

            <div class="w-full max-w-md">

                {{-- Mobile Logo --}}
                <div class="mb-10 flex items-center justify-center gap-3 lg:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-700 text-white shadow-lg">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.5L3 11l9 4.5 9-4.5-9-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 13v4.5c3.3 2.2 6.7 2.2 10 0V13M21 11v6" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">
                            TEFA<span class="text-emerald-600">.</span>
                        </h1>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400">
                            Teaching Factory
                        </p>
                    </div>
                </div>

                {{-- Heading --}}
                <div class="mb-8">
                    <span class="text-sm font-bold uppercase tracking-wider text-emerald-600">
                        Selamat Datang
                    </span>

                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-gray-900">
                        Masuk ke Akun Anda
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-gray-500">
                        Silakan masukkan email dan password untuk mengakses
                        sistem manajemen TEFA.
                    </p>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-5" :status="session('status')" />

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                        >

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                Password
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs font-semibold text-emerald-600 transition hover:text-emerald-700">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                        >

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                        >

                        <label for="remember_me" class="ms-2 text-sm text-gray-600">
                            Ingat saya
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-green-700 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-200/70 transition duration-300 hover:-translate-y-0.5 hover:from-emerald-700 hover:to-green-800 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[0.98]"
                    >
                        Masuk ke Dashboard

                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>

                {{-- Register Link --}}
                @if (Route::has('register'))
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">
                            Belum memiliki akun?

                            <a href="{{ route('register') }}"
                                class="font-bold text-emerald-600 transition hover:text-emerald-700">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                @endif

                {{-- Bottom Info --}}
                <div class="mt-10 flex items-center justify-center gap-2 text-xs text-gray-400">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>

                    Akses aman ke sistem TEFA
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
