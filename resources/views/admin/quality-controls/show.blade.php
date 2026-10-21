@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        <div>
            <a href="{{ route('admin.quality-controls.index') }}"
                class="text-sm font-medium text-green-700 hover:text-green-900">
                ← Kembali ke Quality Control
            </a>

            <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Detail Quality Control
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Informasi lengkap pemeriksaan kualitas produksi.
                    </p>
                </div>

                <a href="{{ route('admin.quality-controls.edit', $qualityControl) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">
                    Edit Pemeriksaan
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div
                class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Nomor Pemeriksaan</p>
                    <h2 class="mt-1 text-lg font-bold text-gray-800">
                        QC-{{ str_pad($qualityControl->id, 4, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>

                <div>
                    @if ($qualityControl->status === 'passed')
                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1.5 text-sm font-semibold text-green-700">
                            Lulus QC
                        </span>
                    @elseif ($qualityControl->status === 'revision')
                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1.5 text-sm font-semibold text-yellow-700">
                            Perlu Revisi
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-gray-200 px-3 py-1.5 text-sm font-semibold text-gray-700">
                            Menunggu Pemeriksaan
                        </span>
                    @endif
                </div>
            </div>

            <div class="space-y-6 p-6">

                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Informasi Produksi
                    </h3>

                    <div class="flex flex-col gap-5 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Nama Produk</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $qualityControl->production?->product?->name ?? 'Produk tidak tersedia' }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-gray-500">ID Produksi</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                #{{ $qualityControl->production_id }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Informasi Pemeriksaan
                    </h3>

                    <div class="flex flex-col gap-5 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Petugas Pemeriksa</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $qualityControl->checkedBy?->name ?? 'Tidak diketahui' }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Tanggal Pemeriksaan</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $qualityControl->checked_at?->format('d M Y, H:i') ?? 'Belum diperiksa' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div>
                    <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Catatan Pemeriksaan
                    </h3>

                    <div class="min-h-24 whitespace-pre-line rounded-lg bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                        {{ $qualityControl->notes ?: 'Belum ada catatan pemeriksaan.' }}
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <p class="text-xs text-gray-400">
                        Dibuat:
                        {{ $qualityControl->created_at?->format('d M Y, H:i') ?? '-' }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Terakhir diperbarui:
                        {{ $qualityControl->updated_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:justify-between">
                <a href="{{ route('admin.quality-controls.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Kembali
                </a>

                <form action="{{ route('admin.quality-controls.destroy', $qualityControl) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data pemeriksaan ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                        Hapus Pemeriksaan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection