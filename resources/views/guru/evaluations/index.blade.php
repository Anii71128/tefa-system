<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="tefa-heading-h3 text-neutral-900">
                    Evaluasi Siswa
                </h2>

                <p class="mt-1 tefa-p12 text-neutral-500">
                    Kelola penilaian siswa berdasarkan hasil produksi
                </p>
            </div>

            <a href="{{ route('guru.evaluations.create') }}"
                class="rounded-lg bg-[#25764C] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#1f6340]">
                + Tambah Evaluasi
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-6">

            {{-- Success --}}
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
                                    Produk
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-700">
                                    Keterampilan
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-700">
                                    Disiplin
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-700">
                                    Kualitas
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-700">
                                    Evaluator
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-700">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($evaluations as $evaluation)
                                <tr class="hover:bg-gray-50">

                                    {{-- Siswa --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $evaluation->student->name }}
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            {{ $evaluation->student->email }}
                                        </div>
                                    </td>

                                    {{-- Produksi --}}
                                    <td class="px-6 py-4 text-gray-700">
                                        #{{ $evaluation->production->id }}
                                    </td>

                                    {{-- Produk --}}
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $evaluation->production->product->name }}
                                    </td>

                                    {{-- Keterampilan --}}
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-semibold text-gray-900">
                                            {{ $evaluation->skill_score }}
                                        </span>
                                    </td>

                                    {{-- Disiplin --}}
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-semibold text-gray-900">
                                            {{ $evaluation->discipline_score }}
                                        </span>
                                    </td>

                                    {{-- Kualitas --}}
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-semibold text-gray-900">
                                            {{ $evaluation->quality_score }}
                                        </span>
                                    </td>

                                    {{-- Evaluator --}}
                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $evaluation->evaluatedBy->name }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            <a href="{{ route('guru.evaluations.show', $evaluation) }}"
                                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                                Detail
                                            </a>

                                        </div>
                                    </td>

                                </tr>
                            @empty

                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            Belum ada data evaluasi
                                        </div>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Tambahkan evaluasi siswa berdasarkan hasil produksi.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if ($evaluations->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $evaluations->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>