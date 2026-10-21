@extends('admin.layouts.app')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div>

            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#25764C]">
                    Dashboard
                </a>

                <span>/</span>

                <a href="{{ route('admin.products.index') }}" class="hover:text-[#25764C]">
                    Produk / Jasa
                </a>

                <span>/</span>

                <span class="text-gray-700">
                    Detail
                </span>

            </div>

            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-[#222934]">
                        Detail Produk
                    </h1>

                    <p class="mt-1 text-sm text-[#6D727E]">
                        Informasi lengkap produk atau jasa.
                    </p>
                </div>

                <div class="flex items-center gap-2">

                    <a href="{{ route('admin.products.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                        Kembali
                    </a>

                    <a href="{{ route('admin.products.edit', $product) }}"
                        class="rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#1f6340]">
                        Edit Produk
                    </a>

                </div>

            </div>

        </div>
{{-- Detail Card --}}
<div class="rounded-xl border border-gray-200 bg-white shadow-sm">

    <div class="p-6">

        {{-- Informasi Utama --}}
        <div class="flex items-start gap-6">

            {{-- Gambar --}}
            <div class="shrink-0">

                @if ($product->image)

                    <img
                        src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        class="h-64 w-64 rounded-xl border border-gray-200 object-cover"
                    >

                @else

                    <div class="flex h-64 w-64 items-center justify-center rounded-xl bg-gray-100 text-sm text-gray-400">
                        Tidak ada gambar
                    </div>

                @endif

            </div>


            {{-- Informasi Produk --}}
            <div class="min-w-0 flex-1">

                {{-- Nama + Status --}}
                <div class="flex items-start justify-between gap-4 border-b border-gray-200 pb-5">

                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-[#6D727E]">
                            Produk / Jasa
                        </p>

                        <h2 class="mt-1 text-2xl font-bold text-[#222934]">
                            {{ $product->name }}
                        </h2>

                    </div>


                    @if ($product->status === 'active')

                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">

                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                            Aktif

                        </span>

                    @else

                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                            Tidak Aktif

                        </span>

                    @endif

                </div>


                {{-- Harga & Stok --}}
                <div class="flex gap-12 border-b border-gray-200 py-5">

                    <div>

                        <p class="text-xs font-medium text-[#6D727E]">
                            Harga
                        </p>

                        <p class="mt-1 text-xl font-bold text-[#25764C]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-medium text-[#6D727E]">
                            Stok
                        </p>

                        <p class="mt-1 text-xl font-bold text-[#222934]">
                            {{ $product->stock }}
                        </p>

                    </div>

                </div>


                {{-- Deskripsi --}}
                <div class="pt-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-[#6D727E]">
                        Deskripsi
                    </p>

                    <p class="mt-2 text-sm leading-6 text-[#4E5560]">
                        {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>

                </div>

{{-- Kategori Produk --}}
<div>
    <p class="text-sm text-gray-500">Kategori</p>

    <p class="mt-1 font-medium text-gray-800">
        {{ $product->category?->name ?? 'Belum ada kategori' }}
    </p>
</div>

            </div>

        </div>


        {{-- Informasi Tambahan --}}
        <div class="mt-6 border-t border-gray-200 pt-5">

            <p class="mb-3 text-sm font-semibold text-[#222934]">
                Informasi Produk
            </p>

            <div class="flex gap-10">

                <div>

                    <p class="text-xs text-[#6D727E]">
                        ID Produk
                    </p>

                    <p class="mt-1 text-sm font-medium text-[#343A46]">
                        #{{ $product->id }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-[#6D727E]">
                        Dibuat
                    </p>

                    <p class="mt-1 text-sm font-medium text-[#343A46]">
                        {{ $product->created_at->format('d M Y, H:i') }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-[#6D727E]">
                        Terakhir diperbarui
                    </p>

                    <p class="mt-1 text-sm font-medium text-[#343A46]">
                        {{ $product->updated_at->format('d M Y, H:i') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

    </div>

@endsection
