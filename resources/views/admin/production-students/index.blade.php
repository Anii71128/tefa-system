@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Penugasan Siswa
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola pembagian tugas siswa dalam kegiatan produksi TEFA.
                </p>
            </div>

            <a href="{{ route('admin.production-students.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                + Tambah Penugasan
            </a>
        </div>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Terjadi kesalahan:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Pencarian dan filter --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form action="{{ route('admin.production-students.index') }}" method="GET"
                class="flex flex-col gap-3 md:flex-row">

                <div class="flex-1">
                    <label for="search" class="mb-1 block text-sm font-medium text-gray-700">
                        Cari penugasan
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari nama produk atau siswa..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div class="w-full md:w-56">
                    <label for="status" class="mb-1 block text-sm font-medium text-gray-700">
                        Status penugasan
                    </label>
                    <select name="status" id="status"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-emerald-500">
                        <option value="">Semua status</option>
                        <option value="assigned" @selected(request('status') === 'assigned')>
                            Ditugaskan
                        </option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>
                            Sedang dikerjakan
                        </option>
                        <option value="completed" @selected(request('status') === 'completed')>
                            Selesai
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        Cari
                    </button>

                    <a href="{{ route('admin.production-students.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel penugasan --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <h2 class="font-semibold text-gray-800">
                    Daftar Penugasan
                </h2>

                <span class="text-sm text-gray-500">
                    Total: {{ $assignments->total() }} penugasan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Produk / Produksi</th>
                            <th class="px-5 py-4">Nama Siswa</th>
                            <th class="px-5 py-4">Tugas</th>
                            <th class="px-5 py-4">Tenggat</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($assignments as $assignment)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $assignments->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $assignment->production?->product?->name ?? 'Produk tidak tersedia' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Produksi #{{ $assignment->production_id }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700">
                                    {{ $assignment->student?->name ?? 'Siswa tidak tersedia' }}
                                </td>

                                <td class="max-w-56 px-5 py-4 text-gray-600">
                                    {{ $assignment->task }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $assignment->deadline?->format('d/m/Y') ?? 'Belum ditentukan' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($assignment->status === 'assigned')
                                        <span
                                            class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                            Ditugaskan
                                        </span>
                                    @elseif ($assignment->status === 'in_progress')
                                        <span
                                            class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
                                            Sedang dikerjakan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                            Selesai
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.production-students.show', $assignment) }}"
                                            class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.production-students.edit', $assignment) }}"
                                            class="rounded-md border border-amber-200 px-3 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.production-students.destroy', $assignment) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus penugasan ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-600">
                                        Belum ada data penugasan siswa.
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Klik tombol Tambah Penugasan untuk membuat data baru.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($assignments->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection