<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Evaluasi Siswa
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Lihat hasil penilaian dan perkembangan kompetensimu.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Total Evaluasi</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-800">
                        {{ $totalEvaluations }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">
                        Penilaian yang diberikan guru
                    </p>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                    <h3 class="mt-2 text-3xl font-bold text-green-700">
                        {{ $averageScore !== null ? number_format($averageScore, 1) : '0,0' }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">
                        Rata-rata tiga aspek penilaian
                    </p>
                </div>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Riwayat Evaluasi
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Daftar hasil evaluasi dari guru.
                    </p>
                </div>

                @if ($evaluations->isEmpty())
                    <div class="py-16 text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z" />
                            </svg>
                        </div>

                        <h4 class="mt-4 font-semibold text-gray-800">
                            Belum Ada Evaluasi
                        </h4>
                        <p class="mt-1 text-sm text-gray-500">
                            Hasil penilaian dari guru akan muncul di sini.
                        </p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($evaluations as $evaluation)
                            @php
                                $average = (
                                    $evaluation->skill_score +
                                    $evaluation->discipline_score +
                                    $evaluation->quality_score
                                ) / 3;
                            @endphp

                            <div class="rounded-xl border border-gray-100 p-5 transition hover:border-green-200">
                                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">
                                            {{ $evaluation->production?->product?->name ?? 'Produk tidak tersedia' }}
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Dinilai oleh
                                            {{ $evaluation->evaluatedBy?->name ?? 'Guru' }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $evaluation->created_at?->format('d M Y') }}
                                        </p>
                                    </div>

                                    <div class="w-fit rounded-lg bg-green-50 px-4 py-2">
                                        <p class="text-xs text-green-700">Rata-rata</p>
                                        <p class="text-xl font-bold text-green-800">
                                            {{ number_format($average, 1) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-lg bg-gray-50 p-3">
                                        <p class="text-xs text-gray-500">Keterampilan</p>
                                        <p class="mt-1 text-lg font-bold text-gray-800">
                                            {{ $evaluation->skill_score }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-gray-50 p-3">
                                        <p class="text-xs text-gray-500">Kedisiplinan</p>
                                        <p class="mt-1 text-lg font-bold text-gray-800">
                                            {{ $evaluation->discipline_score }}
                                        </p>
                                    </div>

                                    <div class="rounded-lg bg-gray-50 p-3">
                                        <p class="text-xs text-gray-500">Kualitas Pekerjaan</p>
                                        <p class="mt-1 text-lg font-bold text-gray-800">
                                            {{ $evaluation->quality_score }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 flex justify-end">
                                    <a href="{{ route('siswa.evaluations.show', $evaluation->id) }}"
                                        class="text-sm font-semibold text-green-700 transition hover:text-green-800">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $evaluations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>