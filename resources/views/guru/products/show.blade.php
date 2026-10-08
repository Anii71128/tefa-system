<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Detail Produk
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap produk Teaching Factory.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-6 lg:px-8">

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex flex-col gap-6 md:flex-row">

                    {{-- Gambar --}}
                    <div class="w-full md:w-1/3">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="h-64 w-full rounded-xl object-cover">
                        @else
                            <div class="flex h-64 items-center justify-center rounded-xl bg-gray-100 text-sm text-gray-400">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    {{-- Informasi --}}
                    <div class="flex flex-1 flex-col gap-5">

                        <div>
                            <p class="text-sm text-gray-500">
                                Nama Produk
                            </p>

                            <h3 class="mt-1 text-2xl font-semibold text-gray-800">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Deskripsi
                            </p>

                            <p class="mt-1 text-sm leading-6 text-gray-700">
                                {{ $product->description ?: 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row">

                            <div class="flex-1">
                                <p class="text-sm text-gray-500">
                                    Harga
                                </p>

                                <p class="mt-1 text-lg font-semibold text-gray-800">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm text-gray-500">
                                    Stok
                                </p>

                                <p class="mt-1 text-lg font-semibold text-gray-800">
                                    {{ $product->stock }}
                                </p>
                            </div>

                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Status
                            </p>

                            <div class="mt-2">
                                @if ($product->status === 'active')
                                    <span
                                        class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <a href="{{ route('guru.products.index') }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Kembali
                    </a>

                    <a href="{{ route('guru.products.edit', $product) }}"
                        class="inline-flex items-center rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1F6040]">
                        Edit Produk
                    </a>

                    <form action="{{ route('guru.products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                            Hapus Produk
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>