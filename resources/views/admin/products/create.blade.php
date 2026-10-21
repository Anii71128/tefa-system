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
                    Tambah
                </span>

            </div>

            <h1 class="text-2xl font-bold text-[#222934]">
                Tambah Produk / Jasa
            </h1>

            <p class="mt-1 text-sm text-[#6D727E]">
                Tambahkan produk atau jasa baru ke dalam sistem Teaching Factory.
            </p>

        </div>


        {{-- Form --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="space-y-6 p-6">

                    {{-- Nama --}}
                    <div>

                        <label for="name" class="mb-2 block text-sm font-semibold text-[#343A46]">
                            Nama Produk / Jasa
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Meja Kayu"
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
                            placeholder="Masukkan deskripsi produk atau jasa..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-[#25764C] focus:ring-1 focus:ring-[#25764C]">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Kategori Produk --}}
                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Kategori <span class="text-red-500">*</span>
                        </label>

                        <select id="category_id" name="category_id" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#25764C] focus:outline-none focus:ring-2 focus:ring-[#25764C]/20">
                            <option value="">Pilih kategori</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Harga & Stok --}}
                    <div class="flex gap-4">

                        <div class="flex-1">

                            <label for="price" class="mb-2 block text-sm font-semibold text-[#343A46]">
                                Harga
                            </label>

                            <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" placeholder="0"
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

                            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0"
                                placeholder="0"
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

                            <option value="active" @selected(old('status', 'active') === 'active')>
                                Aktif
                            </option>

                            <option value="inactive" @selected(old('status') === 'inactive')>
                                Tidak Aktif
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Gambar --}}
                    <div>

                        <label for="image" class="mb-2 block text-sm font-semibold text-[#343A46]">
                            Gambar Produk
                        </label>

                        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">

                        <p class="mt-1 text-xs text-gray-500">
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

                    <a href="{{ route('admin.products.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1f6340]">
                        Simpan Produk
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
