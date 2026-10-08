<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="tefa-heading-h3 text-neutral-900">
                    Detail Quality Control
                </h2>

                <p class="mt-1 tefa-p12 text-neutral-500">
                    Informasi pemeriksaan kualitas produksi
                </p>
            </div>

            <a href="{{ route('guru.quality-controls.index') }}"
                class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-5xl px-6">

            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Quality Control
                    </h3>
                </div>

                <div class="space-y-6 p-6">

                    {{-- Produksi --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Produksi
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            Produksi #{{ $qualityControl->production->id }}
                        </p>
                    </div>

                    {{-- Produk --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Produk
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $qualityControl->production->product->name }}
                        </p>
                    </div>

                    {{-- Pemeriksa --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Pemeriksa
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $qualityControl->checkedBy->name }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $qualityControl->checkedBy->email }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <div class="mt-2">
                            @if ($qualityControl->status === 'pending')
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                    Pending
                                </span>
                            @elseif ($qualityControl->status === 'passed')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    Lulus
                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                    Revisi
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Waktu Pemeriksaan --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Waktu Pemeriksaan
                        </p>

                        <p class="mt-1 text-gray-900">
                            {{ $qualityControl->checked_at?->format('d M Y H:i') ?? '-' }}
                        </p>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <p class="text-sm text-gray-500">
                            Catatan
                        </p>

                        <p class="mt-1 whitespace-pre-line text-gray-900">
                            {{ $qualityControl->notes ?: '-' }}
                        </p>
                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">

                    <a href="{{ route('guru.quality-controls.index') }}"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Kembali
                    </a>

                    <a href="{{ route('guru.quality-controls.edit', $qualityControl) }}"
                        class="rounded-lg bg-[#25764C] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f6340]">
                        Edit
                    </a>

                    <form action="{{ route('guru.quality-controls.destroy', $qualityControl) }}" method="POST"
                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus data Quality Control ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>