<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Perbarui Progres
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Isi perkembangan dan kumpulkan hasil pekerjaanmu.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">

            <a href="{{ route('siswa.progress.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-green-700">
                &larr; Kembali ke Progres
            </a>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <p class="text-sm font-medium text-green-700">
                        Tugas Produksi
                    </p>
                    <h3 class="mt-2 text-xl font-bold text-gray-800">
                        {{ $task->production?->product?->name ?? 'Produk tidak tersedia' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $task->task }}
                    </p>
                </div>

                <div class="mb-6 rounded-lg bg-gray-50 p-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Progres saat ini</span>
                        <span class="font-semibold text-green-700">
                            {{ $task->progress ?? 0 }}%
                        </span>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-gray-200">
                        <div class="h-full rounded-full bg-green-600"
                            style="width: {{ min(100, max(0, $task->progress ?? 0)) }}%">
                        </div>
                    </div>
                </div>

                <form action="{{ route('siswa.progress.update', $task->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">

                    @csrf
                    @method('PUT')

                    <div>
                        <label for="progress" class="mb-2 block text-sm font-semibold text-gray-700">
                            Persentase Progres
                        </label>

                        <div class="flex items-center gap-3">
                            <input type="number" id="progress" name="progress" min="0" max="100"
                                value="{{ old('progress', $task->progress ?? 0) }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">

                            <span class="font-semibold text-gray-600">%</span>
                        </div>

                        @error('progress')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-400">
                            Masukkan angka dari 0 sampai 100.
                        </p>
                    </div>

                    <div>
                        <label for="work_notes" class="mb-2 block text-sm font-semibold text-gray-700">
                            Catatan Pengerjaan
                        </label>

                        <textarea id="work_notes" name="work_notes" rows="5" maxlength="2000"
                            placeholder="Ceritakan perkembangan atau kendala pengerjaan tugas..."
                            class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">{{ old('work_notes', $task->work_notes) }}</textarea>

                        @error('work_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="submission_file" class="mb-2 block text-sm font-semibold text-gray-700">
                            File Hasil Pengerjaan
                        </label>

                        @if ($task->submission_file)
                            <div class="mb-3 rounded-lg border border-green-100 bg-green-50 p-3">
                                <p class="text-sm text-green-800">
                                    File sudah dikumpulkan.
                                </p>
                                <a href="{{ asset('storage/' . $task->submission_file) }}" target="_blank"
                                    class="mt-1 inline-block text-sm font-semibold text-green-700 underline">
                                    Lihat file sebelumnya
                                </a>
                            </div>
                        @endif

                        <input type="file" id="submission_file" name="submission_file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip"
                            class="block w-full rounded-lg border border-gray-300 text-sm text-gray-600 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">

                        @error('submission_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-xs text-gray-400">
                            Format PDF, Word, Excel, PowerPoint, JPG, PNG, atau ZIP. Maksimal 10 MB.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-100 pt-5 sm:flex-row">
                        <a href="{{ route('siswa.progress.index') }}"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">
                            Simpan Progres
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>