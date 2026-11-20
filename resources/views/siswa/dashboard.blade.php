
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Dashboard Siswa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Sambutan --}}
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h1 class="text-2xl font-bold text-gray-800">
                    Halo, {{ auth()->user()->name }} 👋
                </h1>

                <p class="mt-2 text-gray-500">
                    Selamat datang di Dashboard Siswa TEFA.
                    Pantau tugas dan progres pekerjaanmu di sini.
                </p>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Tugas Aktif</p>
                    <h3 class="mt-2 text-3xl font-bold text-blue-600">
                        {{ $activeTasks->count() }}
                    </h3>
                </div>

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Tugas Selesai</p>
                    <h3 class="mt-2 text-3xl font-bold text-green-600">
                        {{ $completedTasks->count() }}
                    </h3>
                </div>

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Terlambat</p>
                    <h3 class="mt-2 text-3xl font-bold text-red-600">
                        {{ $overdueTasks->count() }}
                    </h3>
                </div>

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Rata-rata Progres</p>
                    <h3 class="mt-2 text-3xl font-bold text-[#25764C]">
                        {{ $averageProgress }}%
                    </h3>
                </div>

            </div>

            {{-- Notifikasi --}}
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">
                        Notifikasi Tugas
                    </h2>
                </div>

                @forelse ($notifications as $task)
                    <div class="mb-3 rounded-lg border border-orange-100 bg-orange-50 p-4">
                        <p class="font-semibold text-gray-800">
                            {{ $task->task }}
                        </p>

                        <p class="mt-1 text-sm text-gray-600">
                            Deadline:
                            {{ $task->deadline->format('d M Y') }}
                        </p>

                        @if ($task->deadline->lt(today()))
                            <span class="mt-2 inline-block text-xs font-semibold text-red-600">
                                Deadline telah terlewati
                            </span>
                        @else
                            <span class="mt-2 inline-block text-xs font-semibold text-orange-600">
                                Deadline mendekat
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">
                        Belum ada notifikasi tugas.
                    </p>
                @endforelse
            </div>

            {{-- Deadline terdekat --}}
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-800">
                    Tugas dan Deadline Terdekat
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b text-gray-500">
                                <th class="px-4 py-3">Tugas</th>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Deadline</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($upcomingTasks as $task)
                                <tr class="border-b last:border-0">
                                    <td class="px-4 py-4 font-medium text-gray-800">
                                        {{ $task->task }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-600">
                                        {{ $task->production?->product?->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-600">
                                        {{ $task->deadline->format('d M Y') }}
                                    </td>

                                    <td class="px-4 py-4">
                                        @if ($task->status === 'assigned')
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Belum dikerjakan
                                            </span>
                                        @elseif ($task->status === 'in_progress')
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                Sedang dikerjakan
                                            </span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada tugas yang diberikan guru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Progress pekerjaan --}}
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-bold text-gray-800">
                    Progress Pekerjaan
                </h2>

                @forelse ($tasks as $task)
                    <div class="mb-5 last:mb-0">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <p class="font-medium text-gray-800">
                                {{ $task->task }}
                            </p>

                            <span class="text-sm font-semibold text-[#25764C]">
                                {{ $task->progress }}%
                            </span>
                        </div>

                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-100">
                            <div
                                class="h-full rounded-full bg-[#25764C]"
                                style="width: {{ min(100, max(0, $task->progress)) }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">
                        Belum ada progres pekerjaan.
                    </p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
