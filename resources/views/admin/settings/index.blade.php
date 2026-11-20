@extends('admin.layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="flex flex-wrap items-center gap-2 text-2xl font-bold text-slate-900">
                Pengaturan
                <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-bold uppercase text-emerald-700">
                    Kontrol Sistem & Administrasi
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola profil administrator, keamanan akun, hak akses pengguna,
                cadangan database, dan konfigurasi sistem Teaching Factory.
            </p>
        </div>

        <div class="flex gap-2">
            <button type="button"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                Dokumentasi SOP
            </button>

            <button type="button"
                    class="rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                Simpan Semua Perubahan
            </button>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-[220px_minmax(0,1fr)]">

        {{-- MENU SETTINGS --}}
        <aside class="h-fit rounded-xl border border-slate-200 bg-white p-4 xl:sticky xl:top-24">

            <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Menu Pengaturan
            </p>

            <nav class="space-y-1">

                <a href="#profil"
                   class="settings-link flex items-center gap-2 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-800">
                    <span>♙</span> Profil Admin
                </a>

                <a href="#keamanan"
                   class="settings-link flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                    <span>♧</span> Keamanan & Password
                </a>

                <a href="#pengguna"
                   class="settings-link flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                    <span>♧</span> Manajemen User & Role
                </a>

                <a href="#backup"
                   class="settings-link flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                    <span>▣</span> Backup Database
                </a>

                <a href="#sistem"
                   class="settings-link flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                    <span>⚙</span> Pengaturan Sistem
                </a>

                <a href="#danger"
                   class="settings-link flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                    <span>⚠</span> Danger Zone
                </a>

            </nav>

            {{-- STATUS --}}
            <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 p-3">

                <p class="text-[10px] font-semibold uppercase text-slate-400">
                    Status Server
                </p>

                <div class="mt-2 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-700">
                        {{ app()->environment() }}
                    </span>

                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                        Aktif
                    </span>
                </div>

                <p class="mt-2 text-[10px] text-slate-400">
                    {{ config('database.default') }} Database
                </p>

                <p class="mt-1 text-[10px] text-slate-400">
                    Laravel {{ app()->version() }}
                </p>

            </div>

        </aside>


        {{-- CONTENT SETTINGS --}}
        <div class="min-w-0 space-y-6">

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SECTION 01 PROFIL --}}
            <section id="profil"
                     class="scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 sm:p-6">

                <div class="mb-5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                        Bagian 01
                    </p>

                    <h2 class="text-lg font-bold text-slate-900">
                        Profil Administrator
                    </h2>

                    <p class="text-xs text-slate-500">
                        Perbarui identitas profil pribadi dan kredensial kontak resmi TEFA.
                    </p>
                </div>

                {{-- PROFILE INFO --}}
                <div class="mb-5 flex flex-wrap items-center gap-4 rounded-xl bg-indigo-50 p-4">

                    <div class="flex size-16 items-center justify-center rounded-xl bg-emerald-700 text-xl font-bold text-white">
                        {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                    </div>

                    <div class="min-w-0 flex-1">

                        <h3 class="font-bold text-slate-900">
                            {{ $user?->name ?? 'Administrator' }}
                        </h3>

                        <p class="text-xs text-slate-500">
                            Administrator Sistem
                        </p>

                        <p class="mt-1 break-all text-xs text-emerald-700">
                            {{ $user?->email ?? '-' }}
                        </p>

                    </div>

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-[10px] font-bold text-emerald-700">
                        Aktif
                    </span>

                </div>

                {{-- FORM PROFIL --}}
                <form action="{{ route('admin.settings.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user?->name) }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs outline-none focus:border-emerald-500"
                                   required>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-slate-600">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user?->email) }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs outline-none focus:border-emerald-500"
                                   required>
                        </div>

                    </div>

                    <div class="mt-5 flex justify-end gap-2">

                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-lg px-4 py-2 text-xs text-slate-500 hover:bg-slate-50">
                            Batal
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                            Simpan Profil
                        </button>

                    </div>

                </form>

            </section>


            {{-- SECTION 02 KEAMANAN --}}
            <section id="keamanan"
                     class="scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 sm:p-6">

                <div class="mb-5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                        Bagian 02
                    </p>

                    <h2 class="text-lg font-bold text-slate-900">
                        Keamanan & Kata Sandi
                    </h2>

                    <p class="text-xs text-slate-500">
                        Perbarui kata sandi secara berkala untuk melindungi integritas data finansial & produksi.
                    </p>
                </div>

                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px]">

                    <div class="rounded-xl border border-slate-200 p-4">

                        <form action="{{ route('admin.settings.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-3">

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                        Kata Sandi Saat Ini
                                    </label>

                                    <input type="password"
                                           name="current_password"
                                           autocomplete="current-password"
                                           placeholder="Masukkan kata sandi saat ini"
                                           class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                        Kata Sandi Baru
                                    </label>

                                    <input type="password"
                                           name="password"
                                           autocomplete="new-password"
                                           placeholder="Minimal 8 karakter"
                                           class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">
                                        Konfirmasi Kata Sandi Baru
                                    </label>

                                    <input type="password"
                                           name="password_confirmation"
                                           autocomplete="new-password"
                                           placeholder="Masukkan ulang kata sandi"
                                           class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                                </div>

                            </div>

                            <button type="submit"
                                    class="mt-4 rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                Perbarui Kata Sandi
                            </button>

                        </form>

                    </div>

                    {{-- SECURITY INFO --}}
                    <div class="rounded-xl bg-emerald-50 p-4">

                        <h3 class="text-sm font-bold text-emerald-800">
                            Tips Keamanan TEFA
                        </h3>

                        <ul class="mt-3 space-y-2 text-xs text-slate-600">

                            <li>• Gunakan minimal 8 karakter.</li>
                            <li>• Kombinasikan huruf dan angka.</li>
                            <li>• Jangan membagikan password.</li>
                            <li>• Hindari password yang mudah ditebak.</li>
                            <li>• Logout saat meninggalkan komputer.</li>

                        </ul>

                    </div>

                </div>

            </section>


            {{-- SECTION 03 USER MANAGEMENT --}}
            <section id="pengguna"
                     class="scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 sm:p-6">

                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                            Bagian 03
                        </p>

                        <h2 class="text-lg font-bold text-slate-900">
                            Manajemen Pengguna & Hak Akses
                        </h2>

                        <p class="text-xs text-slate-500">
                            Kelola akun instruktur, siswa, dan pengguna sistem.
                        </p>
                    </div>

                    <button type="button"
                            class="rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                        + Tambah User
                    </button>

                </div>

                {{-- STATS PENGGUNA (READ-ONLY) --}}
                <div class="mb-5 grid grid-cols-2 gap-3 xl:grid-cols-4">

                    @foreach ([
                        ['Total Pengguna', $totalUsers ?? 0],
                        ['Admin', $superAdmins ?? 0],
                        ['Guru / Staff', $teachers ?? 0],
                        ['Siswa', $students ?? 0],
                    ] as $stat)

                        <div class="rounded-xl bg-indigo-50 p-3">

                            <p class="text-[10px] text-slate-500">
                                {{ $stat[0] }}
                            </p>

                            <p class="mt-1 text-xl font-bold text-slate-900">
                                {{ $stat[1] }}
                            </p>

                            <p class="text-[10px] text-slate-400">
                                Data database
                            </p>

                        </div>

                    @endforeach

                </div>

                {{-- FILTER --}}
                <div class="mb-4 flex flex-wrap gap-2">

                    <input type="search"
                           placeholder="Cari nama, NIP, atau username..."
                           class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-xs">

                    <select class="rounded-lg border border-slate-200 px-3 py-2 text-xs">
                        <option>Semua Peran</option>
                        <option>Admin</option>
                        <option>Guru</option>
                        <option>Siswa</option>
                        <option>User</option>
                    </select>

                    <select class="rounded-lg border border-slate-200 px-3 py-2 text-xs">
                        <option>Semua Status</option>
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>

                </div>

                {{-- USER TABLE --}}
                <div class="overflow-x-auto rounded-xl border border-slate-200">

                    <table class="min-w-full text-left text-xs">

                        <thead class="bg-slate-50 text-[10px] uppercase text-slate-500">
                            <tr>
                                <th class="px-3 py-3">Nama</th>
                                <th class="px-3 py-3">Role</th>
                                <th class="px-3 py-3">Status</th>
                                <th class="px-3 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="4"
                                    class="px-3 py-8 text-center text-slate-400">
                                    Manajemen pengguna belum terhubung ke data pengguna.
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>

                {{-- RBAC --}}
                <div class="mt-5 rounded-xl bg-indigo-50 p-4">

                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-800">
                            Matriks Otoritas & Hak Akses (RBAC)
                        </h3>

                        <span class="text-[10px] text-slate-400">
                            Referensi
                        </span>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full text-xs">

                            <thead>
                                <tr class="border-b border-indigo-100 text-slate-500">
                                    <th class="px-2 py-2 text-left">Role</th>
                                    <th class="px-2 py-2">Produk</th>
                                    <th class="px-2 py-2">Produksi</th>
                                    <th class="px-2 py-2">Evaluasi</th>
                                    <th class="px-2 py-2">Admin</th>
                                </tr>
                            </thead>

                            <tbody class="text-center">

                                <tr>
                                    <td class="px-2 py-3 text-left font-medium">Admin</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                </tr>

                                <tr>
                                    <td class="px-2 py-3 text-left font-medium">Guru</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                </tr>

                                <tr>
                                    <td class="px-2 py-3 text-left font-medium">Siswa</td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td>—</td>
                                </tr>

                                <tr>
                                    <td class="px-2 py-3 text-left font-medium">User</td>
                                    <td>✓</td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td>—</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </section>


            {{-- SECTION 04 BACKUP --}}
            <section id="backup"
                     class="scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 sm:p-6">

                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                            Bagian 04
                        </p>

                        <h2 class="text-lg font-bold text-slate-900">
                            Cadangan & Integritas Database
                        </h2>

                        <p class="text-xs text-slate-500">
                            Amankan snapshot data pesanan, konsumen, jurnal kas bengkel, dan riwayat produksi.
                        </p>
                    </div>

                    <form action="{{ route('admin.settings.backup') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                            Backup Database Sekarang
                        </button>
                    </form>

                </div>

                {{-- BACKUP STATUS --}}
                <div class="mb-5 grid gap-3 sm:grid-cols-3">

                    <div class="rounded-xl bg-emerald-50 p-4">
                        <p class="text-xs font-semibold text-emerald-800">
                            Integritas Data
                        </p>
                        <p class="mt-2 text-xs text-slate-500">
                            <span class="font-medium text-emerald-700">Database aktif</span>
                        </p>
                    </div>

                    <div class="rounded-xl bg-indigo-50 p-4">
                        <p class="text-xs font-semibold text-indigo-800">
                            Ukuran File Storage
                        </p>
                        <p class="mt-2 text-xs text-slate-500">
                            {{ isset($databaseSize) ? number_format($databaseSize / 1024 / 1024, 2) . ' MB' : 'Tidak tersedia' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-amber-50 p-4">
                        <p class="text-xs font-semibold text-amber-800">
                            Jadwal Auto-Backup
                        </p>
                        <p class="mt-2 text-xs text-slate-500">
                            Backup manual oleh administrator
                        </p>
                    </div>

                </div>

                <h3 class="mb-3 text-xs font-bold uppercase text-slate-600">
                    Arsip Riwayat Cadangan
                </h3>

                <div class="overflow-x-auto rounded-xl border border-slate-200">

                    <table class="min-w-full text-xs">

                        <thead class="bg-slate-50 text-[10px] uppercase text-slate-500">
                            <tr>
                                <th class="px-3 py-3">Nama File</th>
                                <th class="px-3 py-3">Ukuran</th>
                                <th class="px-3 py-3">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="3"
                                    class="px-3 py-8 text-center text-slate-400">
                                    Belum ada riwayat backup.
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>

            </section>


            {{-- SECTION 05 SYSTEM --}}
            <section id="sistem" class="scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
                <div class="mb-5"><p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Bagian 05</p><h2 class="text-lg font-bold text-slate-900">Identitas Lembaga & Preferensi Sistem</h2><p class="text-xs text-slate-500">Simpan identitas dan preferensi Admin. Toggle ini belum mengaktifkan proses operasional.</p></div>
                <form action="{{ route('admin.settings.system.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Nama Sekolah</label><input type="text" name="school_name" value="{{ old('school_name', $systemSettings['school_name'] ?? 'SMKN 1 Purwosari') }}" required maxlength="255" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs"></div>
                        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Unit / Teaching Factory</label><input type="text" name="tefa_name" value="{{ old('tefa_name', $systemSettings['tefa_name'] ?? 'Teaching Factory') }}" required maxlength="255" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs"></div>
                        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Database Connection</label><input type="text" value="{{ config('database.default') }}" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs"></div>
                    </div>
                    @php $savedAutomation = json_decode($systemSettings['automation'] ?? '[]', true) ?: []; $automationOptions = ['order_notifications' => 'Notifikasi Pesanan Baru', 'production_automation' => 'Otomatisasi Status Produksi', 'stock_alerts' => 'Peringatan Stok Minimum', 'user_audit' => 'Audit Aktivitas Pengguna']; @endphp
                    <div class="mt-6"><h3 class="mb-3 text-xs font-bold uppercase text-slate-600">Preferensi Automasi & Notifikasi</h3><div class="space-y-2">
                        @foreach ($automationOptions as $key => $label)
                            <label class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 p-3"><div><p class="text-xs font-semibold text-slate-700">{{ $label }}</p><p class="text-[10px] text-slate-400">Status preferensi tersimpan; belum mengubah proses operasional.</p></div><input type="checkbox" name="automation[]" value="{{ $key }}" {{ in_array($key, old('automation', $savedAutomation)) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600"></label>
                        @endforeach
                    </div></div>
                    <div class="mt-5 flex justify-end"><button type="submit" class="rounded-lg bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">Simpan Preferensi Sistem</button></div>
                </form>
            </section>


            {{-- SECTION 06 DANGER --}}
            <section id="danger"
                     class="scroll-mt-24 rounded-xl border border-red-200 bg-white p-5 sm:p-6">

                <div class="mb-5">

                    <p class="text-[10px] font-bold uppercase tracking-wider text-red-600">
                        Bagian 06 · Tindakan Tingkat Tinggi
                    </p>

                    <h2 class="text-lg font-bold text-slate-900">
                        Zona Kritis & Pemeliharaan Sistem
                    </h2>

                    <p class="text-xs text-slate-500">
                        Tindakan berikut berdampak langsung pada kelancaran operasional publik dan portal siswa.
                    </p>

                </div>

                <div class="space-y-3">

                    {{-- MAINTENANCE --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 p-4">

                        <div>
                            <p class="text-xs font-bold text-slate-800">
                                Mode Pemeliharaan
                            </p>

                            <p class="text-xs text-slate-500">
                                Aktifkan mode pemeliharaan untuk sementara.
                            </p>
                        </div>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-500">
                            Standby
                        </span>

                    </div>

                    {{-- CACHE --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-slate-200 p-4">

                        <div>
                            <p class="text-xs font-bold text-slate-800">
                                Bersihkan Cache Memori & Session Lama
                            </p>

                            <p class="text-xs text-slate-500">
                                Bersihkan cache aplikasi.
                            </p>
                        </div>

                        <button type="button"
                                class="rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700">
                            Bersihkan Cache
                        </button>

                    </div>

                    {{-- RESET --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-red-100 bg-red-50 p-4">

                        <div>
                            <p class="text-xs font-bold text-red-700">
                                Reset Konfigurasi Pengaturan
                            </p>

                            <p class="text-xs text-slate-500">
                                Tindakan berisiko, belum diaktifkan.
                            </p>
                        </div>

                        <button type="button"
                                disabled
                                class="cursor-not-allowed rounded-lg bg-red-100 px-3 py-2 text-xs font-semibold text-red-400">
                            Reset Konfigurasi
                        </button>

                    </div>

                </div>

            </section>

        </div>

    </div>

</div>

@endsection