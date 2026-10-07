<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Tambah Produk
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan produk baru Teaching Factory.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-6 lg:px-8">

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <form action="{{ route('guru.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col gap-5">

                        {{-- Nama Produk --}}
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Produk
                            </label>

                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama produk"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:ring-green-600">

                            @error('name')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                                Deskripsi
                            </label>

                            <textarea id="description" name="description" rows="4"
                                placeholder="Masukkan deskripsi produk"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:ring-green-600">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Harga & Stok --}}
                        <div class="flex flex-col gap-5 md:flex-row">

                            <div class="flex-1">
                                <label for="price" class="mb-2 block text-sm font-medium text-gray-700">
                                    Harga
                                </label>

                                <input type="number" id="price" name="price" value="{{ old('price') }}" min="0"
                                    placeholder="Contoh: 1500000"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:ring-green-600">

                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex-1">
                                <label for="stock" class="mb-2 block text-sm font-medium text-gray-700">
                                    Stok
                                </label>

                                <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0"
                                    placeholder="Contoh: 10"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:ring-green-600">

                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>

                        {{-- Gambar --}}
                        <div>
                            <label for="image" class="mb-2 block text-sm font-medium text-gray-700">
                                Gambar Produk
                            </label>

                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                            <p class="mt-1 text-xs text-gray-500">
                                Format gambar: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                            </p>

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                                Status
                            </label>

                            <select id="status" name="status"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:ring-green-600">
                                <option value="active" @selected(old('status', 'active') === 'active')>
                                    Aktif
                                </option>

                                <option value="inactive" @selected(old('status') === 'inactive')>
                                    Tidak Aktif
                                </option>
                            </select>

                            @error('status')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-200 pt-6">

                            <a href="{{ route('guru.products.index') }}"
                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1F6040]">
                                Simpan Produk
                            </button>

                        </div>
                    </div>

            </div>
            </form>

        </div>

    </div>
    </div>
</x-app-layout>