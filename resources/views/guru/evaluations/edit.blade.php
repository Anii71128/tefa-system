<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-h3 text-neutral-900">
                Edit Evaluasi Siswa
            </h2>

            <p class="mt-1 tefa-p12 text-neutral-500">
                Perbarui hasil evaluasi siswa
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-6">

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Form Edit Evaluasi
                    </h3>
                </div>

                <form
                    action="{{ route('guru.evaluations.update', $evaluation) }}"
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
                                    @selected(old('production_id', $evaluation->production_id) == $production->id)
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

                    {{-- Siswa --}}
                    <div>
                        <label
                            for="student_id"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Siswa
                        </label>

                        <select
                            name="student_id"
                            id="student_id"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            required
                        >
                            <option value="">Pilih Siswa</option>

                            @foreach ($students as $student)
                                <option
                                    value="{{ $student->id }}"
                                    @selected(old('student_id', $evaluation->student_id) == $student->id)
                                >
                                    {{ $student->name }}
                                    - {{ $student->email }}
                                </option>
                            @endforeach
                        </select>

                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterampilan --}}
                    <div>
                        <label
                            for="skill_score"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Nilai Keterampilan
                        </label>

                        <input
                            type="number"
                            name="skill_score"
                            id="skill_score"
                            min="0"
                            max="100"
                            value="{{ old('skill_score', $evaluation->skill_score) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            required
                        >

                        @error('skill_score')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Disiplin --}}
                    <div>
                        <label
                            for="discipline_score"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Nilai Disiplin
                        </label>

                        <input
                            type="number"
                            name="discipline_score"
                            id="discipline_score"
                            min="0"
                            max="100"
                            value="{{ old('discipline_score', $evaluation->discipline_score) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            required
                        >

                        @error('discipline_score')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kualitas --}}
                    <div>
                        <label
                            for="quality_score"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Nilai Kualitas Kerja
                        </label>

                        <input
                            type="number"
                            name="quality_score"
                            id="quality_score"
                            min="0"
                            max="100"
                            value="{{ old('quality_score', $evaluation->quality_score) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            required
                        >

                        @error('quality_score')
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
                            Catatan Evaluasi
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="4"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]"
                            placeholder="Masukkan catatan evaluasi..."
                        >{{ old('notes', $evaluation->notes) }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">

                        <a
                            href="{{ route('guru.evaluations.show', $evaluation) }}"
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
