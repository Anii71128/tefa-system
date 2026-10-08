<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-h3 text-neutral-900">
                Edit Quality Control
            </h2>

            <p class="mt-1 tefa-p12 text-neutral-500">
                Perbarui hasil pemeriksaan kualitas produksi
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-6">

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Form Edit Quality Control
                    </h3>
                </div>

                <form
                    action="{{ route('guru.quality-controls.update', $qualityControl) }}"
                    method="POST"
                    class="space-y-6 p-6"
                >
                    @csrf
                    @method('PUT')

                    {{-- Produksi --}}
                    <div>
                        <label
                            for="production_id"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Produksi
                        </label>

                        <select
                            name="production_id"
                            id="production_id"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            required
                        >
                            <option value="">Pilih Produksi</option>

                            @foreach ($productions as $production)
                                <option
                                    value="{{ $production->id }}"
                                    @selected(old('production_id', $qualityControl->production_id) == $production->id)
                                >
                                    Produksi #{{ $production->id }}
                                    - {{ $production->product->name }}
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
                        <label
                            for="status"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Status Quality Control
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            required
                        >
                            <option
                                value="pending"
                                @selected(old('status', $qualityControl->status) === 'pending')
                            >
                                Pending
                            </option>

                            <option
                                value="passed"
                                @selected(old('status', $qualityControl->status) === 'passed')
                            >
                                Lulus
                            </option>

                            <option
                                value="revision"
                                @selected(old('status', $qualityControl->status) === 'revision')
                            >
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
                        <label
                            for="notes"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Catatan Pemeriksaan
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            placeholder="Masukkan catatan hasil pemeriksaan..."
                        >{{ old('notes', $qualityControl->notes) }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Waktu Pemeriksaan --}}
                    <div>
                        <label
                            for="checked_at"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Waktu Pemeriksaan
                        </label>

                        <input
                            type="datetime-local"
                            name="checked_at"
                            id="checked_at"
                            value="{{ old('checked_at', $qualityControl->checked_at?->format('Y-m-d\TH:i')) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                        >

                        @error('checked_at')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">

                        <a
                            href="{{ route('guru.quality-controls.show', $qualityControl) }}"
                            class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#1f6340]"
                        >
                            Simpan Perubahan
                        </button>

                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
