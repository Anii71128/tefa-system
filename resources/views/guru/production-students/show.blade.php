<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    Penugasan Siswa / Detail
                </p>

                <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                    Detail Penugasan
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi penugasan siswa pada kegiatan produksi.
                </p>
            </div>

            <a href="{{ route('guru.production-students.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Kembali
            </a>

        </div>

        {{-- Detail Card --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Penugasan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Detail data penugasan yang telah dibuat.
                </p>
            </div>

            <div class="p-6">

                <div class="flex flex-col gap-6">

                    {{-- Siswa --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Siswa
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            {{ $productionStudent->student->name }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $productionStudent->student->email }}
                        </p>
                    </div>

                    {{-- Produksi --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Produksi
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            {{ $productionStudent->production->product->name }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Produksi #{{ $productionStudent->production->id }}
                        </p>
                    </div>

                    {{-- Tugas --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Tugas
                        </p>

                        <div class="mt-2 rounded-lg bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                            {{ $productionStudent->task }}
                        </div>
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Deadline
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            @if ($productionStudent->deadline)
                                {{ $productionStudent->deadline->format('d M Y') }}
                            @else
                                Tidak ditentukan
                            @endif
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <div class="mt-2">

                            @if ($productionStudent->status === 'assigned')

                                <span
                                    class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                                    Ditugaskan
                                </span>

                            @elseif ($productionStudent->status === 'in_progress')

                                <span
                                    class="inline-flex rounded-full bg-yellow-50 px-3 py-1 text-sm font-medium text-yellow-700">
                                    Sedang Dikerjakan
                                </span>

                            @else

                                <span
                                    class="inline-flex rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700">
                                    Selesai
                                </span>

                            @endif

                        </div>
                    </div>

                </div>

            </div>
            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">

                <a href="{{ route('guru.production-students.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>

                <form action="{{ route('guru.production-students.destroy', $productionStudent) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </form>

                <a href="{{ route('guru.production-students.edit', $productionStudent) }}"
                    class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]">
                    Edit Penugasan
                </a>

            </div>

        </div>

    </div>

</x-app-layout>