<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Produksi / Detail
                </p>

                <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                    Detail Produksi
                </h1>
            </div>

            <a href="{{ route('guru.productions.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
        </div>

        {{-- Detail Card --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Produksi
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Detail data produksi yang telah dibuat.
                </p>
            </div>

            <div class="p-6">

                <div class="flex flex-col gap-6">

                    {{-- Produk --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Produk
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            {{ $production->product->name }}
                        </p>
                    </div>

                    {{-- Guru --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Guru Penanggung Jawab
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            {{ $production->guru->name }}
                        </p>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Jumlah Produksi
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            {{ $production->quantity }}
                        </p>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Tanggal Produksi
                        </p>

                        <p class="mt-1 text-base font-medium text-gray-900">
                            {{ $production->production_date->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <div class="mt-2">
                            @php
                                $statusLabels = [
                                    'planned' => 'Direncanakan',
                                    'in_progress' => 'Sedang Produksi',
                                    'qc' => 'Quality Control',
                                    'revision' => 'Revisi',
                                    'completed' => 'Selesai',
                                ];
                            @endphp

                            <span
                                class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                {{ $statusLabels[$production->status] ?? $production->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                Progress Produksi
                            </p>

                            <span class="text-sm font-semibold text-gray-900">
                                {{ $production->progress }}%
                            </span>
                        </div>

                        <div class="mt-2 h-3 w-full overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full rounded-full bg-[#25764C]" style="width: {{ $production->progress }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Catatan
                        </p>

                        <div class="mt-2 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                            {{ $production->notes ?: 'Tidak ada catatan.' }}
                        </div>
                    </div>

                </div>

            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">

                <a href="{{ route('guru.productions.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>

                <form action="{{ route('guru.productions.destroy', $production) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data produksi ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </form>

                <a href="{{ route('guru.productions.edit', $production) }}"
                    class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]">
                    Edit Produksi
                </a>

            </div>

        </div>

    </div>

</x-app-layout>