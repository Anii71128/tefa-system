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
                Tambah Penugasan Siswa
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tentukan kegiatan produksi dan tugas yang harus dikerjakan siswa.
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

        {{-- Form --}}
        <form action="{{ route('admin.production-students.store') }}" method="POST"
            class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            @csrf

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
                            <option value="{{ $production->id }}" @selected(old('production_id') == $production->id)>
                                Produksi #{{ $production->id }}
                                — {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                                ({{ $production->getRawOriginal('status') }})
                            </option>
                        @endforeach
                    </select>

                    @if ($productions->isEmpty())
                        <p class="mt-2 text-sm text-amber-600">
                            Belum ada kegiatan produksi. Tambahkan data produksi terlebih dahulu.
                        </p>
                    @endif

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
                            <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                {{ $student->name }} — {{ $student->email }}
                            </option>
                        @endforeach
                    </select>

                    @if ($students->isEmpty())
                        <p class="mt-2 text-sm text-amber-600">
                            Belum ada akun dengan role siswa. Buat atau periksa akun siswa terlebih dahulu.
                        </p>
                    @endif

                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tugas --}}
                <div class="md:col-span-2">
                    <label for="task" class="mb-2 block text-sm font-semibold text-gray-700">
                        Deskripsi Tugas <span class="text-red-500">*</span>
                    </label>

                    <textarea name="task" id="task" rows="4" required maxlength="255"
                        placeholder="Contoh: Melakukan perakitan produk sesuai instruksi guru."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('task') }}</textarea>

                    @error('task')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tenggat --}}
                <div>
                    <label for="deadline" class="mb-2 block text-sm font-semibold text-gray-700">
                        Tenggat Penyelesaian
                    </label>

                    <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">

                    <p class="mt-1 text-xs text-gray-400">
                        Boleh dikosongkan jika belum ditentukan.
                    </p>

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
                        <option value="assigned" @selected(old('status', 'assigned') === 'assigned')>
                            Ditugaskan
                        </option>

                        <option value="in_progress" @selected(old('status') === 'in_progress')>
                            Sedang dikerjakan
                        </option>

                        <option value="completed" @selected(old('status') === 'completed')>
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

                <button type="submit" @disabled($productions->isEmpty() || $students->isEmpty())
                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                    Simpan Penugasan
                </button>
            </div>

        </form>
    </div>
@endsection