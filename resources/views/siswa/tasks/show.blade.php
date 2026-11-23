<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Detail Tugas Produksi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap tugas produksi.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">

            <a href="{{ route('siswa.tasks.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-green-700">
                <span>&larr;</span>
                Kembali ke Tugas Produksi
            </a>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-sm font-medium text-green-700">
                            Tugas Produksi
                        </p>
                        <h3 class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $task->production?->product?->name ?? 'Produk tidak tersedia' }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Diberikan oleh:
                            {{ $task->production?->guru?->name ?? 'Guru belum tersedia' }}
                        </p>
                    </div>

                    @if ($task->status === 'completed')
                        <span class="w-fit rounded-full bg-green-50 px-4 py-2 text-sm font-medium text-green-700">
                            Selesai
                        </span>
                    @elseif ($task->status === 'in_progress')
                        <span class="w-fit rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700">
                            Sedang Dikerjakan
                        </span>
                    @else
                        <span class="w-fit rounded-full bg-yellow-50 px-4 py-2 text-sm font-medium text-yellow-700">
                            Menunggu
                        </span>
                    @endif
                </div>

                <div class="my-6 border-t border-gray-100"></div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Nama Tugas</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $task->task }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Deadline</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $task->deadline?->format('d F Y') ?? 'Belum ditentukan' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Jumlah Produksi</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $task->production?->quantity ?? 0 }} unit
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Produksi</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $task->production?->production_date?->format('d F Y') ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="my-6 border-t border-gray-100"></div>

                <div>
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-gray-800">
                            Progres Pengerjaan
                        </h4>
                        <span class="font-bold text-green-700">
                            {{ $task->progress ?? 0 }}%
                        </span>
                    </div>

                    <div class="mt-3 h-3 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-green-600 transition-all"
                            style="width: {{ min(100, max(0, $task->progress ?? 0)) }}%">
                        </div>
                    </div>
                </div>

                <div class="my-6 border-t border-gray-100"></div>

                <div>
                    <h4 class="font-semibold text-gray-800">
                        Catatan Tugas
                    </h4>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">
                        {{ $task->work_notes ?: 'Belum ada catatan pengerjaan.' }}
                    </p>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold text-gray-800">
                        File Pengumpulan
                    </h4>

                    @if ($task->submission_file)
                        <p class="mt-2 text-sm text-green-700">
                            File sudah dikumpulkan.
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-500">
                            Belum ada file yang dikumpulkan.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>