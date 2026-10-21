@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        <div>
            <a href="{{ route('admin.evaluations.index') }}"
                class="text-sm font-medium text-green-700 hover:text-green-900">
                ← Kembali ke Evaluasi Siswa
            </a>

            <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Detail Evaluasi Siswa
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Rincian hasil penilaian siswa dalam kegiatan TEFA.
                    </p>
                </div>

                <a href="{{ route('admin.evaluations.edit', $evaluation) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">
                    Edit Evaluasi
                </a>
            </div>
        </div>

        @php
            $average = (
                $evaluation->skill_score +
                $evaluation->discipline_score +
                $evaluation->quality_score
            ) / 3;
        @endphp

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 bg-gray-50 px-6 py-5">
                <p class="text-sm text-gray-500">Nomor Evaluasi</p>
                <h2 class="mt-1 text-lg font-bold text-gray-800">
                    EV-{{ str_pad($evaluation->id, 4, '0', STR_PAD_LEFT) }}
                </h2>
            </div>

            <div class="space-y-6 p-6">

                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Informasi Siswa
                    </h3>

                    <div class="flex flex-col gap-5 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Nama Siswa</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $evaluation->student?->name ?? 'Siswa tidak tersedia' }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-gray-500">ID Siswa</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $evaluation->student_id }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Informasi Produksi dan Penilai
                    </h3>

                    <div class="flex flex-col gap-5 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Produk</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $evaluation->production?->product?->name ?? 'Produk tidak tersedia' }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                Produksi #{{ $evaluation->production_id }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Guru/Admin Penilai</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $evaluation->evaluatedBy?->name ?? 'Penilai tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Hasil Penilaian
                    </h3>

                    <div class="flex flex-col gap-4 sm:flex-row">
                        <div class="flex-1 rounded-xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Keterampilan</p>
                            <p class="mt-2 text-3xl font-bold text-green-700">
                                {{ $evaluation->skill_score }}
                                <span class="text-sm font-medium text-gray-400">/ 100</span>
                            </p>
                        </div>

                        <div class="flex-1 rounded-xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Kedisiplinan</p>
                            <p class="mt-2 text-3xl font-bold text-green-700">
                                {{ $evaluation->discipline_score }}
                                <span class="text-sm font-medium text-gray-400">/ 100</span>
                            </p>
                        </div>

                        <div class="flex-1 rounded-xl border border-gray-200 p-4">
                            <p class="text-sm text-gray-500">Kualitas Kerja</p>
                            <p class="mt-2 text-3xl font-bold text-green-700">
                                {{ $evaluation->quality_score }}
                                <span class="text-sm font-medium text-gray-400">/ 100</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl bg-green-50 p-5">
                        <p class="text-sm font-medium text-green-800">
                            Nilai Rata-rata
                        </p>
                        <p class="mt-1 text-3xl font-bold text-green-700">
                            {{ number_format($average, 1, ',', '.') }}
                            <span class="text-sm font-medium text-green-600">/ 100</span>
                        </p>
                        <p class="mt-2 text-xs text-green-700">
                            Dihitung dari rata-rata tiga aspek penilaian.
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div>
                    <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Catatan Evaluasi
                    </h3>

                    <div class="min-h-24 whitespace-pre-line rounded-lg bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                        {{ $evaluation->notes ?: 'Belum ada catatan evaluasi.' }}
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <p class="text-xs text-gray-400">
                        Dibuat:
                        {{ $evaluation->created_at?->format('d M Y, H:i') ?? '-' }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Terakhir diperbarui:
                        {{ $evaluation->updated_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

            </div>

            <div
                class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.evaluations.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Kembali
                </a>

                <form action="{{ route('admin.evaluations.destroy', $evaluation) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus evaluasi ini?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                        Hapus Evaluasi
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection