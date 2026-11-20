
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Tugas Produksi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Daftar tugas produksi yang diberikan kepadamu.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Daftar Tugas Saya
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Total {{ $tasks->total() }} tugas produksi.
                        </p>
                    </div>
                </div>

                @if ($tasks->isEmpty())
                    <div class="py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h4 class="mt-4 font-semibold text-gray-800">
                            Belum Ada Tugas
                        </h4>
                        <p class="mt-1 text-sm text-gray-500">
                            Tugas produksi yang diberikan guru akan muncul di sini.
                        </p>
                    </div>
                @else
                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full min-w-[700px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="px-4 py-3">No.</th>
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="px-4 py-3">Tugas</th>
                                    <th class="px-4 py-3">Deadline</th>
                                    <th class="px-4 py-3">Progres</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
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
                                                {{ $task->production?->guru?->name ?? 'Guru belum tersedia' }}
                                            </p>
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $task->task }}
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $task->deadline?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="h-2 w-20 overflow-hidden rounded-full bg-gray-100">
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

                                        <td class="px-4 py-4">
                                            <a
                                                href="{{ route('siswa.tasks.show', $task->id) }}"
                                                class="font-semibold text-green-700 hover:text-green-800">
                                                Detail
                                            </a>
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
