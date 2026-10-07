<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500">
                Quality Control / Tambah
            </p>

            <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                Tambah Quality Control
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Periksa kualitas hasil produksi.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <form action="{{ route('guru.quality-controls.store') }}" method="POST">

                @csrf

                <div class="flex flex-col gap-6 p-6">

                    {{-- Produksi --}}
                    <div>
                        <label for="production_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Produksi
                        </label>

                        <select id="production_id" name="production_id"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                            <option value="">
                                Pilih produksi
                            </option>

                            @foreach ($productions as $production)

                                <option value="{{ $production->id }}" @selected(old('production_id') == $production->id)>
                                    {{ $production->product->name }}
                                    — Produksi #{{ $production->id }}
                                    — Qty {{ $production->quantity }}
                                </option>

                            @endforeach

                        </select>

                        @error('production_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                            Status Quality Control
                        </label>

                        <select id="status" name="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                            <option value="pending" @selected(old('status', 'pending') === 'pending')>
                                Pending
                            </option>

                            <option value="passed" @selected(old('status') === 'passed')>
                                Lulus
                            </option>

                            <option value="revision" @selected(old('status') === 'revision')>
                                Revisi
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                            Catatan Pemeriksaan
                        </label>

                        <textarea id="notes" name="notes" rows="5"
                            placeholder="Tuliskan hasil pemeriksaan atau catatan QC..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Waktu Pemeriksaan --}}
                    <div>
                        <label for="checked_at" class="mb-2 block text-sm font-medium text-gray-700">
                            Waktu Pemeriksaan
                        </label>

                        <input type="datetime-local" id="checked_at" name="checked_at" value="{{ old('checked_at') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                        @error('checked_at')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">

                    <a href="{{ route('guru.quality-controls.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]">
                        Simpan QC
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>