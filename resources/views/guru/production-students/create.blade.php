<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500">
                Penugasan Siswa / Tambah
            </p>

            <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                Tambah Penugasan Siswa
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tugaskan siswa ke kegiatan produksi.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <form action="{{ route('guru.production-students.store') }}" method="POST">

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
                        <label for="student_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Siswa
                        </label>

                        <select id="student_id" name="student_id"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                            <option value="">
                                Pilih siswa
                            </option>

                            @foreach ($students as $student)

                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->name }}
                                    — {{ $student->email }}
                                </option>

                            @endforeach

                        </select>

                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tugas --}}
                    <div>
                        <label for="task" class="mb-2 block text-sm font-medium text-gray-700">
                            Tugas
                        </label>

                        <textarea id="task" name="task" rows="4"
                            placeholder="Contoh: Melakukan pengecekan dan perakitan produk..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">{{ old('task') }}</textarea>

                        @error('task')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <label for="deadline" class="mb-2 block text-sm font-medium text-gray-700">
                            Deadline
                        </label>

                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select id="status" name="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#25764C] focus:ring-[#25764C]">

                            <option value="assigned" @selected(old('status', 'assigned') === 'assigned')>
                                Ditugaskan
                            </option>

                            <option value="in_progress" @selected(old('status') === 'in_progress')>
                                Sedang Dikerjakan
                            </option>

                            <option value="completed" @selected(old('status') === 'completed')>
                                Selesai
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">

                    <a href="{{ route('guru.production-students.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]">
                        Simpan Penugasan
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>