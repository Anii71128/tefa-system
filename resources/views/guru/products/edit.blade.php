<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-2 text-[#222934]">
                Edit Produk
            </h2>

            <p class="tefa-p-14 mt-1 text-[#6D727E]">
                Perbarui informasi produk yang tersedia.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <form action="{{ route('guru.products.update', $product) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Produk --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Produk
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mt-5">
                        <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>

                        <textarea id="description" name="description" rows="5"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Harga & Stok --}}
                    <div class="mt-5 flex gap-5">

                        <div class="flex-1">
                            <label for="price" class="mb-2 block text-sm font-medium text-gray-700">
                                Harga
                            </label>

                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}"
                                min="0" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

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

                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}"
                                min="0" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Gambar --}}
                    <div class="mt-5">
                        <label for="image" class="mb-2 block text-sm font-medium text-gray-700">
                            Gambar Produk
                        </label>

                        @if ($product->image)
                            <div class="mb-4">
                                <p class="mb-2 text-sm text-gray-500">
                                    Gambar saat ini:
                                </p>

                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="h-40 w-40 rounded-lg object-cover">
                            </div>
                        @endif

                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm file:font-medium">

                        <p class="mt-1 text-xs text-gray-500">
                            Kosongkan jika tidak ingin mengganti gambar.
                        </p>

                        @error('image')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mt-5">
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select id="status" name="status" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">
                            <option value="active" @selected(old('status', $product->status) === 'active')>
                                Aktif
                            </option>

                            <option value="inactive" @selected(old('status', $product->status) === 'inactive')>
                                Tidak Aktif
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-200 pt-6">

                        <a href="{{ route('guru.products.show', $product) }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1F6040]">
                            Update Produk
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>