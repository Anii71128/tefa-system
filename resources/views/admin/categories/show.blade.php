
@extends('admin.layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <a href="{{ route('admin.categories.index') }}"
            class="text-sm text-gray-500 hover:text-[#25764C]">
            ← Kembali ke Kategori
        </a>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Detail Kategori
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap kategori TEFA.
                </p>
            </div>

            <a href="{{ route('admin.categories.edit', $category) }}"
                class="rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#1d603d]">
                Edit Kategori
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold text-gray-800">
                Informasi Kategori
            </h2>
        </div>

        <div class="space-y-5 p-6">
            <div>
                <p class="text-sm text-gray-500">Nama Kategori</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $category->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Deskripsi</p>
                <p class="mt-1 whitespace-pre-line text-gray-800">
                    {{ $category->description ?: 'Belum ada deskripsi.' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>

                @if ($category->status === 'active')
                    <span class="mt-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                        Aktif
                    </span>
                @else
                    <span class="mt-2 inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                        Tidak Aktif
                    </span>
                @endif
            </div>

            <div>
                <p class="text-sm text-gray-500">Dibuat pada</p>
                <p class="mt-1 text-gray-800">
                    {{ $category->created_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Terakhir diperbarui</p>
                <p class="mt-1 text-gray-800">
                    {{ $category->updated_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
