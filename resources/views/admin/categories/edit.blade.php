@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">

        <div>
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-[#25764C]">
                ← Kembali ke Kategori
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Edit Kategori
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi kategori yang dipilih.
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-5">

                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>

                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#25764C] focus:outline-none focus:ring-2 focus:ring-[#25764C]/20">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                        Deskripsi
                    </label>

                    <textarea id="description" name="description" rows="4"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#25764C] focus:outline-none focus:ring-2 focus:ring-[#25764C]/20">{{ old('description', $category->description) }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select id="status" name="status" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#25764C] focus:outline-none focus:ring-2 focus:ring-[#25764C]/20">

                        <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>
                            Tidak Aktif
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('admin.categories.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1d603d]">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection