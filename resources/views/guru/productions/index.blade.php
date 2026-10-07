<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-2 text-[#222934]">
                Produksi
            </h2>

            <p class="tefa-p-14 mt-1 text-[#6D727E]">
                Kelola dan monitor proses produksi TEFA.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-5 flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-[#222934]">
                        Data Produksi
                    </h3>

                    <p class="mt-1 text-sm text-[#6D727E]">
                        Daftar seluruh proses produksi yang dikelola guru.
                    </p>
                </div>

                <a href="{{ route('guru.productions.create') }}"
                    class="inline-flex items-center rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1F6040]">
                    + Tambah Produksi
                </a>

            </div>


            {{-- Table --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="border-b border-gray-200 bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Produk
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Guru
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Jumlah
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Tanggal Produksi
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Status
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Progress
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse ($productions as $production)

                                <tr class="hover:bg-gray-50">

                                    {{-- Product --}}
                                    <td class="px-6 py-4">

                                        <p class="font-medium text-gray-700">
                                            {{ $production->product->name ?? '-' }}
                                        </p>

                                    </td>


                                    {{-- Guru --}}
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $production->guru->name ?? '-' }}
                                    </td>


                                    {{-- Quantity --}}
                                    <td class="px-6 py-4 text-center text-gray-600">
                                        {{ $production->quantity }}
                                    </td>


                                    {{-- Production Date --}}
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $production->production_date?->format('d M Y') ?? '-' }}
                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        @php
                                            $statusClasses = [
                                                'planned' => 'bg-gray-100 text-gray-700',
                                                'in_progress' => 'bg-blue-100 text-blue-700',
                                                'qc' => 'bg-yellow-100 text-yellow-700',
                                                'revision' => 'bg-red-100 text-red-700',
                                                'completed' => 'bg-green-100 text-green-700',
                                            ];

                                            $statusLabels = [
                                                'planned' => 'Direncanakan',
                                                'in_progress' => 'Sedang Produksi',
                                                'qc' => 'Quality Control',
                                                'revision' => 'Revisi',
                                                'completed' => 'Selesai',
                                            ];
                                        @endphp

                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses[$production->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$production->status] ?? ucfirst($production->status) }}
                                        </span>

                                    </td>


                                    {{-- Progress --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-100">

                                                <div class="h-full rounded-full bg-[#25764C]"
                                                    style="width: {{ $production->progress }}%"></div>

                                            </div>

                                            <span class="text-xs font-medium text-gray-600">
                                                {{ $production->progress }}%
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Action --}}
                                    <td class="px-6 py-4 text-center">

                                        <a href="{{ route('guru.productions.show', $production) }}"
                                            class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                            Detail
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="px-6 py-12 text-center">

                                        <div class="text-sm text-gray-500">
                                            Belum ada data produksi.
                                        </div>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Data produksi akan muncul setelah produksi dibuat.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($productions->hasPages())

                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $productions->links() }}
                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>