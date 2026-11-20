
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Riwayat Tugas
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Lihat kembali seluruh tugas dan hasil pengerjaanmu.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-6 flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Semua Riwayat Pekerjaan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Total {{ $tasks->total() }} tugas yang pernah diberikan.
                        </p>
                    </div>

                    <a
                        href="{{ route('siswa.tasks.index') }}"
                        class="inline-flex w-fit items-center rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-800">
                        Lihat Tugas Aktif
                    </a>
                </div>

                @if ($tasks->isEmpty())
                    <div class="py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h4 class="mt-4 font-semibold text-gray-800">
                            Belum Ada Riwayat Tugas
                        </h4>
                        <p class="mt-1 text-sm text-gray-500">
                            Tugas yang pernah diberikan akan ditampilkan di sini.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[850px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="px-4 py-3">No.</th>
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="px-4 py-3">Tugas</th>
                                    <th class="px-4 py-3">Tanggal Produksi</th>
                                    <th class="px-4 py-3">Deadline</th>
                                    <th class="px-4 py-3">Progres</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach ($tasks as $index => $task)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-4 py-4 text-gray-500">
                                            {{ $tasks->firstItem() + $index }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <p class="font-semibold text-gray-800">
                                                {{ $task->production?->product?->name ?? 'Produk tidak tersedia' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Guru:
                                                {{ $task->production?->guru?->name ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $task->task }}
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $task->production?->production_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $task->deadline?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="h-2 w-16 overflow-hidden rounded-full bg-gray-100">
                                                    <div
                                                        class="h-full rounded-full bg-green-600"
                                                        style="width: {{ min(100, max(0, $task->progress ?? 0)) }}%">
                                                    </div>
                                                </div>
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ $task->progress ?? 0 }}%
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
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
                                        </td>
                                    </tr>

                                    <tr class="bg-gray-50/70">
                                        <td colspan="7" class="px-4 py-3">
                                            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                                <div class="min-w-0">
                                                    <p class="text-xs font-semibold text-gray-500">
                                                        Catatan Pengerjaan
                                                    </p>
                                                    <p class="mt-1 whitespace-pre-line text-sm text-gray-600">
                                                        {{ $task->work_notes ?: 'Belum ada catatan.' }}
                                                    </p>
                                                </div>

                                                @if ($task->submission_file)
                                                    <a
                                                        href="{{ asset('storage/' . $task->submission_file) }}"
                                                        target="_blank"
                                                        class="shrink-0 text-sm font-semibold text-green-700 hover:text-green-800">
                                                        Lihat File Pengumpulan
                                                    </a>
                                                @else
                                                    <span class="shrink-0 text-xs text-gray-400">
                                                        Belum ada file
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
