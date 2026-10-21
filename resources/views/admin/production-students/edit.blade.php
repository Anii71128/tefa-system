@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div>
            <a href="{{ route('admin.production-students.index') }}"
                class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                ← Kembali ke Penugasan Siswa
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Edit Penugasan Siswa
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi penugasan siswa sesuai kebutuhan kegiatan produksi.
            </p>
        </div>

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Periksa kembali data berikut:</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form edit --}}
        <form action="{{ route('admin.production-students.update', $productionStudent) }}" method="POST"
            class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Kegiatan produksi --}}
                <div class="md:col-span-2">
                    <label for="production_id" class="mb-2 block text-sm font-semibold text-gray-700">
                        Kegiatan Produksi <span class="text-red-500">*</span>
                    </label>

                    <select name="production_id" id="production_id" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Pilih kegiatan produksi</option>

                        @foreach ($productions as $production)
                            <option value="{{ $production->id }}" @selected(old('production_id', $productionStudent->production_id) == $production->id)>
                                Produksi #{{ $production->id }}
                                — {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                                ({{ $production->getRawOriginal('status') }})
                            </option>
                        @endforeach
                    </select>

                    @error('production_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Siswa --}}
                <div class="md:col-span-2">
                    <label for="student_id" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nama Siswa <span class="text-red-500">*</span>
                    </label>

                    <select name="student_id" id="student_id" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Pilih siswa</option>

                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(old('student_id', $productionStudent->student_id) == $student->id)>
                                {{ $student->name }} — {{ $student->email }}
                            </option>
                        @endforeach
                    </select>

                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi tugas --}}
                <div class="md:col-span-2">
                    <label for="task" class="mb-2 block text-sm font-semibold text-gray-700">
                        Deskripsi Tugas <span class="text-red-500">*</span>
                    </label>

                    <textarea name="task" id="task" rows="4" maxlength="255" required
                        placeholder="Tuliskan tugas yang harus dikerjakan siswa."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('task', $productionStudent->task) }}</textarea>

                    @error('task')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tenggat --}}
                <div>
                    <label for="deadline" class="mb-2 block text-sm font-semibold text-gray-700">
                        Tenggat Penyelesaian
                    </label>

                    <input type="date" name="deadline" id="deadline"
                        value="{{ old('deadline', $productionStudent->deadline?->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">

                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                        Status Penugasan <span class="text-red-500">*</span>
                    </label>

                    <select name="status" id="status" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                        <option value="assigned" @selected(old('status', $productionStudent->status) === 'assigned')>
                            Ditugaskan
                        </option>

                        <option value="in_progress" @selected(old('status', $productionStudent->status) === 'in_progress')>
                            Sedang dikerjakan
                        </option>

                        <option value="completed" @selected(old('status', $productionStudent->status) === 'completed')>
                            Selesai
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.production-students.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
@endsection