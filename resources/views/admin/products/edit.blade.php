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
                    Edit
                </span>

            </div>

            <h1 class="text-2xl font-bold text-[#222934]">
                Edit Produk / Jasa
            </h1>

            <p class="mt-1 text-sm text-[#6D727E]">
                Perbarui informasi produk atau jasa.
            </p>

        </div>


        {{-- Form --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="space-y-6 p-6">

                    {{-- Nama --}}
                    <div>

                        <label for="name" class="mb-2 block text-sm font-semibold text-[#343A46]">
                            Nama Produk / Jasa
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]">

                        @error('name')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Deskripsi --}}
                    <div>

                        <label for="description" class="mb-2 block text-sm font-semibold text-[#343A46]">
                            Deskripsi
                        </label>

                        <textarea id="description" name="description" rows="4"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]">{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
                    <option value="">Pilih kategori</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                    {{-- Harga & Stok --}}
                    <div class="flex gap-4">

                        <div class="flex-1">

                            <label for="price" class="mb-2 block text-sm font-semibold text-[#343A46]">
                                Harga
                            </label>

                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]">

                            @error('price')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <div class="flex-1">

                            <label for="stock" class="mb-2 block text-sm font-semibold text-[#343A46]">
                                Stok
                            </label>

                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]">

                            @error('stock')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label for="status" class="mb-2 block text-sm font-semibold text-[#343A46]">
                            Status
                        </label>

                        <select id="status" name="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]">

                            <option value="active" @selected(old('status', $product->status) === 'active')>
                                Aktif
                            </option>

                            <option value="inactive" @selected(old('status', $product->status) === 'inactive')>
                                Tidak Aktif
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Gambar Lama --}}
                    @if ($product->image)

                        <div>

                            <p class="mb-2 text-sm font-semibold text-[#343A46]">
                                Gambar Saat Ini
                            </p>

                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="h-40 w-40 rounded-xl border border-gray-200 object-cover">

                        </div>

                    @endif


                    {{-- Gambar Baru --}}
                    <div>

                        <label for="image" class="mb-2 block text-sm font-semibold text-[#343A46]">
                            Ganti Gambar
                        </label>

                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">

                        <p class="mt-1 text-xs text-gray-500">
                            Kosongkan jika tidak ingin mengganti gambar.
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>

                        @error('image')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">

                    <a href="{{ route('admin.products.show', $product) }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1f6340]">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
