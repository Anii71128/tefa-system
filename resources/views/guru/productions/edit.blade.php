<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500">
                Produksi / Edit
            </p>

            <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                Edit Produksi
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi dan progres produksi.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <form
                action="{{ route('guru.productions.update', $production) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="flex flex-col gap-6 p-6">

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
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >

                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    @selected(old('product_id', $production->product_id) == $product->id)
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

                    {{-- Quantity --}}
                    <div>
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
                            min="1"
                            value="{{ old('quantity', $production->quantity) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >

                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div>
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
                            value="{{ old('production_date', $production->production_date->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >

                        @error('production_date')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label
                            for="status"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Status Produksi
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >

                            <option
                                value="planned"
                                @selected(old('status', $production->status) === 'planned')
                            >
                                Direncanakan
                            </option>

                            <option
                                value="in_progress"
                                @selected(old('status', $production->status) === 'in_progress')
                            >
                                Sedang Produksi
                            </option>

                            <option
                                value="qc"
                                @selected(old('status', $production->status) === 'qc')
                            >
                                Quality Control
                            </option>

                            <option
                                value="revision"
                                @selected(old('status', $production->status) === 'revision')
                            >
                                Revisi
                            </option>

                            <option
                                value="completed"
                                @selected(old('status', $production->status) === 'completed')
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

                    {{-- Progress --}}
                    <div>
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
                            min="0"
                            max="100"
                            value="{{ old('progress', $production->progress) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >

                        @error('progress')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label
                            for="notes"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Catatan
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >{{ old('notes', $production->notes) }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">

                    <a
                        href="{{ route('guru.productions.show', $production) }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]"
                    >
                        Update Produksi
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
