<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    Operasional / Penugasan Siswa
                </p>

                <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                    Penugasan Siswa
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola penugasan siswa dalam kegiatan produksi.
                </p>
            </div>

            <a
                href="{{ route('guru.production-students.create') }}"
                class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]"
            >
                + Tambah Penugasan
            </a>

        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Siswa
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Produksi
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Tugas
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Deadline
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right font-semibold text-gray-700">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($productionStudents as $assignment)

                            <tr class="hover:bg-gray-50">

                                {{-- Siswa --}}
                                <td class="px-6 py-4">

                                    <p class="font-medium text-gray-900">
                                        {{ $assignment->student->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $assignment->student->email }}
                                    </p>

                                </td>

                                {{-- Produksi --}}
                                <td class="px-6 py-4">

                                    <p class="font-medium text-gray-900">
                                        {{ $assignment->production->product->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Produksi #{{ $assignment->production->id }}
                                    </p>

                                </td>

                                {{-- Tugas --}}
                                <td class="max-w-xs px-6 py-4 text-gray-700">
                                    {{ $assignment->task }}
                                </td>

                                {{-- Deadline --}}
                                <td class="px-6 py-4 text-gray-700">

                                    @if ($assignment->deadline)
                                        {{ $assignment->deadline->format('d M Y') }}
                                    @else
                                        <span class="text-gray-400">
                                            -
                                        </span>
                                    @endif

                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    @php
                                        $statusLabels = [
                                            'assigned' => 'Ditugaskan',
                                            'in_progress' => 'Sedang Dikerjakan',
                                            'completed' => 'Selesai',
                                        ];
                                    @endphp

                                    @if ($assignment->status === 'assigned')
                                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                            {{ $statusLabels[$assignment->status] }}
                                        </span>

                                    @elseif ($assignment->status === 'in_progress')
                                        <span class="inline-flex rounded-full bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700">
                                            {{ $statusLabels[$assignment->status] }}
                                        </span>

                                    @else
                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                            {{ $statusLabels[$assignment->status] }}
                                        </span>
                                    @endif

                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('guru.production-students.show', $assignment) }}"
                                        class="font-medium text-[#25764C] hover:underline"
                                    >
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center"
                                >

                                    <p class="font-medium text-gray-900">
                                        Belum ada penugasan siswa
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Tambahkan penugasan siswa ke kegiatan produksi.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($productionStudents->hasPages())

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $productionStudents->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>
