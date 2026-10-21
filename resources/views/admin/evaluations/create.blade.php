@extends('admin.layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.evaluations.index') }}"
           class="text-sm font-medium text-green-700 hover:text-green-900">
            ← Kembali ke Evaluasi Siswa
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">
            Tambah Evaluasi Siswa
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Masukkan hasil penilaian siswa selama kegiatan produksi TEFA.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <form action="{{ route('admin.evaluations.store') }}"
              method="POST"
              class="space-y-6">
            @csrf

            <div>
                <label for="production_id"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Produksi <span class="text-red-500">*</span>
                </label>

                <select name="production_id"
                        id="production_id"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                    <option value="">-- Pilih Produksi --</option>

                    @foreach ($productions as $production)
                        <option value="{{ $production->id }}"
                            @selected(old('production_id') == $production->id)>
                            Produksi #{{ $production->id }} —
                            {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Siswa yang Dinilai <span class="text-red-500">*</span>
                </label>

                <select name="student_id"
                        id="student_id"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                    <option value="">-- Pilih Siswa --</option>

                    @foreach ($students as $student)
                        <option value="{{ $student->id }}"
                            @selected(old('student_id') == $student->id)>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="evaluated_by"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Guru/Admin Penilai <span class="text-red-500">*</span>
                </label>

                <select name="evaluated_by"
                        id="evaluated_by"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                    <option value="">-- Pilih Penilai --</option>

                    @foreach ($evaluators as $evaluator)
                        <option value="{{ $evaluator->id }}"
                            @selected(old('evaluated_by') == $evaluator->id)>
                            {{ $evaluator->name }} ({{ ucfirst($evaluator->role) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="border-t border-gray-100 pt-5">
                <h2 class="text-lg font-bold text-gray-800">
                    Aspek Penilaian
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Masukkan nilai setiap aspek dengan rentang 0–100.
                </p>
            </div>

            <div class="flex flex-col gap-5 sm:flex-row">
                <div class="flex-1">
                    <label for="skill_score"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Keterampilan <span class="text-red-500">*</span>
                    </label>

                    <input type="number"
                           name="skill_score"
                           id="skill_score"
                           min="0"
                           max="100"
                           step="1"
                           required
                           value="{{ old('skill_score') }}"
                           placeholder="Contoh: 85"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                </div>

                <div class="flex-1">
                    <label for="discipline_score"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Kedisiplinan <span class="text-red-500">*</span>
                    </label>

                    <input type="number"
                           name="discipline_score"
                           id="discipline_score"
                           min="0"
                           max="100"
                           step="1"
                           required
                           value="{{ old('discipline_score') }}"
                           placeholder="Contoh: 90"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                </div>

                <div class="flex-1">
                    <label for="quality_score"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Kualitas Kerja <span class="text-red-500">*</span>
                    </label>

                    <input type="number"
                           name="quality_score"
                           id="quality_score"
                           min="0"
                           max="100"
                           step="1"
                           required
                           value="{{ old('quality_score') }}"
                           placeholder="Contoh: 88"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                </div>
            </div>

            <div>
                <label for="notes"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Catatan Evaluasi
                </label>

                <textarea name="notes"
                          id="notes"
                          rows="4"
                          placeholder="Tuliskan perkembangan, kelebihan, atau hal yang perlu ditingkatkan..."
                          class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">{{ old('notes') }}</textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.evaluations.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                    Simpan Evaluasi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
