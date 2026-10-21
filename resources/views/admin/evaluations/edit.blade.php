@extends('admin.layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.evaluations.index') }}"
           class="text-sm font-medium text-green-700 hover:text-green-900">
            ← Kembali ke Evaluasi Siswa
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">
            Edit Evaluasi Siswa
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui hasil penilaian siswa pada kegiatan TEFA.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <form action="{{ route('admin.evaluations.update', $evaluation) }}"
              method="POST"
              class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="production_id" class="mb-2 block text-sm font-semibold text-gray-700">
                    Produksi
                </label>

                <select name="production_id" id="production_id" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">
                    <option value="">Pilih Produksi</option>

                    @foreach ($productions as $production)
                        <option value="{{ $production->id }}"
                            {{ old('production_id', $evaluation->production_id) == $production->id ? 'selected' : '' }}>
                            {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                            — Produksi #{{ $production->id }}
                        </option>
                    @endforeach
                </select>

                @error('production_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="student_id" class="mb-2 block text-sm font-semibold text-gray-700">
                    Siswa
                </label>

                <select name="student_id" id="student_id" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">
                    <option value="">Pilih Siswa</option>

                    @foreach ($students as $student)
                        <option value="{{ $student->id }}"
                            {{ old('student_id', $evaluation->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>

                @error('student_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="evaluated_by" class="mb-2 block text-sm font-semibold text-gray-700">
                    Guru/Admin Penilai
                </label>

                <select name="evaluated_by" id="evaluated_by" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">
                    <option value="">Pilih Penilai</option>

                    @foreach ($evaluators as $evaluator)
                        <option value="{{ $evaluator->id }}"
                            {{ old('evaluated_by', $evaluation->evaluated_by) == $evaluator->id ? 'selected' : '' }}>
                            {{ $evaluator->name }} ({{ ucfirst($evaluator->role) }})
                        </option>
                    @endforeach
                </select>

                @error('evaluated_by')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-5">
                <h2 class="mb-4 text-lg font-bold text-gray-800">
                    Nilai Evaluasi
                </h2>

                <div class="flex flex-col gap-5 sm:flex-row">
                    <div class="flex-1">
                        <label for="skill_score" class="mb-2 block text-sm font-semibold text-gray-700">
                            Keterampilan (0–100)
                        </label>

                        <input type="number" name="skill_score" id="skill_score"
                               min="0" max="100" step="1" required
                               value="{{ old('skill_score', $evaluation->skill_score) }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">

                        @error('skill_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex-1">
                        <label for="discipline_score" class="mb-2 block text-sm font-semibold text-gray-700">
                            Kedisiplinan (0–100)
                        </label>

                        <input type="number" name="discipline_score" id="discipline_score"
                               min="0" max="100" step="1" required
                               value="{{ old('discipline_score', $evaluation->discipline_score) }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">

                        @error('discipline_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex-1">
                        <label for="quality_score" class="mb-2 block text-sm font-semibold text-gray-700">
                            Kualitas Kerja (0–100)
                        </label>

                        <input type="number" name="quality_score" id="quality_score"
                               min="0" max="100" step="1" required
                               value="{{ old('quality_score', $evaluation->quality_score) }}"
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">

                        @error('quality_score')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="mb-2 block text-sm font-semibold text-gray-700">
                    Catatan Evaluasi
                </label>

                <textarea name="notes" id="notes" rows="4"
                          placeholder="Masukkan catatan atau saran untuk siswa..."
                          class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none">{{ old('notes', $evaluation->notes) }}</textarea>

                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.evaluations.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg bg-green-700 px-5 py-3 text-sm font-semibold text-white hover:bg-green-800">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
