@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">

        <div>
            <a href="{{ route('admin.productions.index') }}"
                class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                ← Kembali ke Produksi
            </a>

            <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Produksi</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Informasi lengkap proses produksi Teaching Factory.
                    </p>
                </div>

                <a href="{{ route('admin.productions.edit', $production) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Edit Produksi
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

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

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Jumlah Produksi</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ number_format($production->quantity) }}
                </p>
                <p class="mt-1 text-xs text-gray-500">Unit yang direncanakan</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Progres Produksi</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ $production->progress }}%
                </p>

                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-gray-200">
                    <div class="h-full rounded-full bg-emerald-500" style="width: {{ $production->progress }}%"></div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Status Produksi</p>
                <div class="mt-3">
                    <span
                        class="inline-flex rounded-full px-3 py-1.5 text-sm font-semibold {{ $statusColors[$production->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$production->status] ?? $production->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="font-semibold text-gray-800">Informasi Produksi</h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">Nama Produk</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $production->product->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Guru Penanggung Jawab</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $production->guru->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Produksi</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $production->production_date?->format('d F Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Data Dibuat</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $production->created_at?->format('d F Y, H:i') ?? '-' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-gray-500">Catatan Produksi</p>
                    <div class="mt-2 rounded-lg bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                        {{ $production->notes ?: 'Tidak ada catatan produksi.' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
            <form action="{{ route('admin.productions.destroy', $production) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus data produksi ini?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                    class="w-full rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 sm:w-auto">
                    Hapus Produksi
                </button>
            </form>

            <a href="{{ route('admin.productions.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                Kembali ke Daftar
            </a>
        </div>

    </div>
@endsection