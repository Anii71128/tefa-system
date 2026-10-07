<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    Operasional / Quality Control
                </p>

                <h1 class="mt-1 text-2xl font-semibold text-gray-900">
                    Quality Control
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola pemeriksaan kualitas hasil produksi.
                </p>
            </div>

            <a
                href="{{ route('guru.quality-controls.create') }}"
                class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1e633f]"
            >
                + Tambah QC
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
                                Produksi
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Produk
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Pemeriksa
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Status
                            </th>

                            <th class="px-6 py-4 font-semibold text-gray-700">
                                Waktu Pemeriksaan
                            </th>

                            <th class="px-6 py-4 text-right font-semibold text-gray-700">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($qualityControls as $qualityControl)

                            <tr class="hover:bg-gray-50">

                                {{-- Produksi --}}
                                <td class="px-6 py-4">

                                    <p class="font-medium text-gray-900">
                                        Produksi #{{ $qualityControl->production->id }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Qty:
                                        {{ $qualityControl->production->quantity }}
                                    </p>

                                </td>

                                {{-- Produk --}}
                                <td class="px-6 py-4 text-gray-700">

                                    {{ $qualityControl->production->product->name }}

                                </td>

                                {{-- Pemeriksa --}}
                                <td class="px-6 py-4">

                                    <p class="font-medium text-gray-900">
                                        {{ $qualityControl->checkedBy->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $qualityControl->checkedBy->email }}
                                    </p>

                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    @if ($qualityControl->status === 'pending')

                                        <span class="inline-flex rounded-full bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-700">
                                            Pending
                                        </span>

                                    @elseif ($qualityControl->status === 'passed')

                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                                            Lulus
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">
                                            Revisi
                                        </span>

                                    @endif

                                </td>

                                {{-- Checked At --}}
                                <td class="px-6 py-4 text-gray-700">

                                    @if ($qualityControl->checked_at)

                                        {{ $qualityControl->checked_at->format('d M Y H:i') }}

                                    @else

                                        <span class="text-gray-400">
                                            Belum diperiksa
                                        </span>

                                    @endif

                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('guru.quality-controls.show', $qualityControl) }}"
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
                                        Belum ada data Quality Control
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Tambahkan pemeriksaan kualitas pada hasil produksi.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($qualityControls->hasPages())

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $qualityControls->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>
