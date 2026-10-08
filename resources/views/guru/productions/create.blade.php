<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-2 text-[#222934]">
                Tambah Produksi
            </h2>

            <p class="tefa-p-14 mt-1 text-[#6D727E]">
                Buat data produksi baru untuk produk TEFA.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <form
                    action="{{ route('guru.productions.store') }}"
                    method="POST"
                >
                    @csrf

                    {{-- Produk --}}
                    <div>
                        <label
                            for="product_id"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Produk
                        </label>

                        <select
                            id="product_id"
                            name="product_id"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >
                            <option value="">
                                Pilih Produk
                            </option>

                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    @selected(old('product_id') == $product->id)
                                >
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Jumlah & Tanggal --}}
                    <div class="mt-5 flex gap-5">

                        <div class="flex-1">
                            <label
                                for="quantity"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Jumlah Produksi
                            </label>

                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                value="{{ old('quantity') }}"
                                min="1"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            >

                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        <div class="flex-1">
                            <label
                                for="production_date"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Tanggal Produksi
                            </label>

                            <input
                                type="date"
                                id="production_date"
                                name="production_date"
                                value="{{ old('production_date', now()->format('Y-m-d')) }}"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            >

                            @error('production_date')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>


                    {{-- Status & Progress --}}
                    <div class="mt-5 flex gap-5">

                        <div class="flex-1">
                            <label
                                for="status"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Status Produksi
                            </label>

                            <select
                                id="status"
                                name="status"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            >
                                <option
                                    value="planned"
                                    @selected(old('status', 'planned') === 'planned')
                                >
                                    Direncanakan
                                </option>

                                <option
                                    value="in_progress"
                                    @selected(old('status') === 'in_progress')
                                >
                                    Sedang Produksi
                                </option>

                                <option
                                    value="qc"
                                    @selected(old('status') === 'qc')
                                >
                                    Quality Control
                                </option>

                                <option
                                    value="revision"
                                    @selected(old('status') === 'revision')
                                >
                                    Revisi
                                </option>

                                <option
                                    value="completed"
                                    @selected(old('status') === 'completed')
                                >
                                    Selesai
                                </option>
                            </select>

                            @error('status')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        <div class="flex-1">
                            <label
                                for="progress"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Progress (%)
                            </label>

                            <input
                                type="number"
                                id="progress"
                                name="progress"
                                value="{{ old('progress', 0) }}"
                                min="0"
                                max="100"
                                required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            >

                            @error('progress')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>


                    {{-- Catatan --}}
                    <div class="mt-5">

                        <label
                            for="notes"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Catatan
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="5"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            placeholder="Tambahkan catatan produksi jika diperlukan..."
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-200 pt-6">

                        <a
                            href="{{ route('guru.productions.index') }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1F6040]"
                        >
                            Simpan Produksi
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
