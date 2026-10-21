@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Produksi</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola dan pantau proses produksi Teaching Factory.
            </p>
        </div>

        <a href="{{ route('admin.productions.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
            + Tambah Produksi
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form action="{{ route('admin.productions.index') }}" method="GET"
            class="flex flex-col gap-3 md:flex-row">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama produk atau guru..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">

            <select name="status"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-emerald-500">
                <option value="">Semua Status</option>
                <option value="planned" @selected(request('status') === 'planned')>Direncanakan</option>
                <option value="in_progress" @selected(request('status') === 'in_progress')>Sedang Berjalan</option>
                <option value="qc" @selected(request('status') === 'qc')>Quality Control</option>
                <option value="revision" @selected(request('status') === 'revision')>Revisi</option>
                <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
            </select>

            <button type="submit"
                class="rounded-lg bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
                Cari
            </button>

            <a href="{{ route('admin.productions.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-600 hover:bg-gray-50">
                Reset
            </a>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">No.</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Produk</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Guru Penanggung Jawab</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Jumlah</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Progres</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-gray-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($productions as $production)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                {{ $productions->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4 text-sm font-medium text-gray-800">
                                {{ $production->product->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $production->guru->name ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                {{ $production->quantity }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                {{ $production->production_date?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="min-w-36 px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200">
                                        <div class="h-full rounded-full bg-emerald-500"
                                            style="width: {{ $production->progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-gray-600">
                                        {{ $production->progress }}%
                                    </span>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @php
                                    $statusLabels = [
                                        'planned' => 'Direncanakan',
                                        'in_progress' => 'Sedang Berjalan',
                                        'qc' => 'Quality Control',
                                        'revision' => 'Revisi',
                                        'completed' => 'Selesai',
                                    ];

                                    $statusColors = [
                                        'planned' => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'qc' => 'bg-amber-100 text-amber-700',
                                        'revision' => 'bg-red-100 text-red-700',
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$production->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$production->status] ?? $production->status }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.productions.show', $production) }}"
                                        class="rounded-md bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.productions.edit', $production) }}"
                                        class="rounded-md bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.productions.destroy', $production) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data produksi ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">📦</span>
                                    <p class="font-semibold text-gray-700">Belum ada data produksi</p>
                                    <p class="text-sm text-gray-500">
                                        Tambahkan data produksi untuk mulai memantau proses produksi.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($productions->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $productions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
