@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#25764C]">
                    Dashboard
                </a>
                <span>/</span>
                <span>Kategori</span>
            </div>

            <h1 class="mt-2 text-2xl font-bold text-[#222934]">
                Kategori Produk
            </h1>

            <p class="mt-1 text-sm text-[#6D727E]">
                Kelola kategori produk dan jasa Teaching Factory.
            </p>
        </div>

        <a
            href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1f6340]"
        >
            + Tambah Kategori
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-base font-semibold text-[#222934]">
                Daftar Kategori
            </h2>
        </div>

        @if ($categories->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                                No
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                                Nama Kategori
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                                Deskripsi
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                                Status
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-[#222934]">
                                        {{ $category->name }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="max-w-md truncate text-sm text-[#6D727E]">
                                        {{ $category->description ?: '-' }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($category->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a
                                            href="{{ route('admin.categories.show', $category) }}"
                                            class="text-sm font-medium text-[#25764C] hover:underline"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="{{ route('admin.categories.edit', $category) }}"
                                            class="text-sm font-medium text-blue-600 hover:underline"
                                        >
                                            Edit
                                        </a>

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif
                                        <form
                                            action="{{ route('admin.categories.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-sm font-medium text-red-600 hover:underline"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $categories->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    📁
                </div>

                <h3 class="mt-4 text-sm font-semibold text-[#222934]">
                    Belum ada kategori
                </h3>

                <p class="mt-1 text-sm text-[#6D727E]">
                    Tambahkan kategori pertama untuk mengelompokkan produk dan jasa.
                </p>

                <a
                    href="{{ route('admin.categories.create') }}"
                    class="mt-4 inline-flex items-center rounded-lg bg-[#25764C] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1f6340]"
                >
                    + Tambah Kategori
                </a>
            </div>
        @endif

    </div>

</div>
@endsection
