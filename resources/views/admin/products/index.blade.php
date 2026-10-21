@extends('admin.layouts.app')

@section('content')

<div class="space-y-6">

    {{-- Header Halaman --}}
    <div class="flex items-center justify-between">

        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="hover:text-[#25764C]"
                >
                    Dashboard
                </a>

                <span>/</span>

                <span class="text-gray-700">
                    Produk / Jasa
                </span>
            </div>

            <h1 class="text-2xl font-bold text-[#222934]">
                Produk / Jasa
            </h1>

            <p class="mt-1 text-sm text-[#6D727E]">
                Kelola data produk dan jasa Teaching Factory.
            </p>
        </div>

        {{-- Tombol Tambah --}}
        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1f6340]"
        >
            <span class="text-lg leading-none">+</span>
            Tambah Produk
        </a>

    </div>


    {{-- Alert Success --}}
    @if (session('success'))

        <div class="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100">
                ✓
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- Filter & Search --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <form
            action="{{ route('admin.products.index') }}"
            method="GET"
            class="flex items-center gap-3"
        >

            {{-- Search --}}
            <div class="relative flex-1">

                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    ⌕
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk atau jasa..."
                    class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-4 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]"
                >

            </div>


            {{-- Tombol Cari --}}
            <button
                type="submit"
                class="rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1f6340]"
            >
                Cari
            </button>


            {{-- Reset --}}
            @if (request('search'))

                <a
                    href="{{ route('admin.products.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                >
                    Reset
                </a>

            @endif

        </form>

    </div>


    {{-- Tabel --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Header Tabel --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

            <div>
                <h2 class="text-base font-semibold text-[#222934]">
                    Daftar Produk / Jasa
                </h2>

                <p class="mt-1 text-xs text-[#6D727E]">
                    Menampilkan {{ $products->total() }} produk.
                </p>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                            Produk
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                            Harga
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                            Stok
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                            Status
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                            Aksi
                        </th>

                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($products as $product)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Produk --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    @if ($product->image)

                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="h-11 w-11 rounded-lg border border-gray-200 object-cover"
                                        >

                                    @else

                                        <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100 text-xs text-gray-400">
                                            N/A
                                        </div>

                                    @endif


                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-semibold text-[#222934]">
                                            {{ $product->name }}
                                        </p>

                                        <p class="mt-0.5 max-w-xs truncate text-xs text-[#6D727E]">
                                            {{ $product->description ?: 'Tidak ada deskripsi' }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Harga --}}
                            <td class="px-5 py-4">

                                <span class="text-sm font-semibold text-[#222934]">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>

                            </td>


                            {{-- Stok --}}
                            <td class="px-5 py-4">

                                @if ($product->stock <= 0)

                                    <span class="text-sm font-semibold text-red-600">
                                        Habis
                                    </span>

                                @elseif ($product->stock <= 5)

                                    <span class="text-sm font-semibold text-orange-600">
                                        {{ $product->stock }}
                                    </span>

                                @else

                                    <span class="text-sm text-[#4E5560]">
                                        {{ $product->stock }}
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if ($product->status === 'active')

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                        Aktif

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">

                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                        Tidak Aktif

                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td class="px-5 py-4">

                                <div class="flex justify-end items-center gap-2">

                                    {{-- Detail --}}
                                    <a
                                        href="{{ route('admin.products.show', $product) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-50"
                                    >
                                        Detail
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="rounded-lg bg-[#25764C] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1f6340]"
                                    >
                                        Edit
                                    </a>


                                  <form
    action="{{ route('admin.products.destroy', $product) }}"
    method="POST"
    onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
>
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="text-sm font-medium text-red-600 hover:text-red-700"
    >
        Hapus
    </button>
</form>
                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-5 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                                        □
                                    </div>

                                    <p class="text-sm font-semibold text-[#4E5560]">
                                        Belum ada produk
                                    </p>

                                    <p class="mt-1 text-xs text-[#6D727E]">
                                        Tambahkan produk atau jasa untuk mulai mengelola katalog.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($products->hasPages())

            <div class="border-t border-gray-200 px-5 py-4">

                {{ $products->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
