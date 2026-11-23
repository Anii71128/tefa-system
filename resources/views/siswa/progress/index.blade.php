
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Progres Pengerjaan
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Pantau dan perbarui perkembangan tugas produksimu.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Progres Tugas Saya
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Perbarui progres dan kumpulkan hasil pekerjaanmu.
                    </p>
                </div>

                @if ($tasks->isEmpty())
                    <div class="py-14 text-center">
                        <h4 class="font-semibold text-gray-800">
                            Belum Ada Tugas
                        </h4>
                        <p class="mt-1 text-sm text-gray-500">
                            Tugas yang diberikan guru akan muncul di sini.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($tasks as $task)
                            <div class="rounded-xl border border-gray-100 p-5 transition hover:border-green-200">
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $task->production?->product?->name ?? 'Produk tidak tersedia' }}
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $task->task }}
                                        </p>

                                        <p class="mt-2 text-xs text-gray-400">
                                            Deadline:
                                            {{ $task->deadline?->format('d M Y') ?? 'Belum ditentukan' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($task->status === 'completed')
                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                                Selesai
                                            </span>
                                        @elseif ($task->status === 'in_progress')
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                                Dikerjakan
                                            </span>
                                        @else
                                            <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700">
                                                Menunggu
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Progres pengerjaan</span>
                                        <span class="font-semibold text-gray-800">
                                            {{ $task->progress ?? 0 }}%
                                        </span>
                                    </div>

                                    <div class="h-2.5 overflow-hidden rounded-full bg-gray-100">
                                        <div
                                            class="h-full rounded-full bg-green-600"
                                            style="width: {{ min(100, max(0, $task->progress ?? 0)) }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-xs text-gray-500">
                                        @if ($task->submission_file)
                                            <span class="text-green-700">File sudah dikumpulkan</span>
                                        @else
                                            Belum ada file pengumpulan
                                        @endif
                                    </p>

                                    <a
                                        href="{{ route('siswa.progress.edit', $task->id) }}"
                                        class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800">
                                        Perbarui Progres
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
