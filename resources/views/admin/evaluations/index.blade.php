@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Evaluasi Siswa</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola penilaian keterampilan, kedisiplinan, dan kualitas kerja siswa.
                </p>
            </div>

            <a href="{{ route('admin.evaluations.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                + Tambah Evaluasi
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form action="{{ route('admin.evaluations.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama siswa atau produk..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">

                <button type="submit"
                    class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-900">
                    Cari
                </button>

                <a href="{{ route('admin.evaluations.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-center text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Siswa</th>
                            <th class="px-5 py-4">Produk / Produksi</th>
                            <th class="px-5 py-4">Keterampilan</th>
                            <th class="px-5 py-4">Kedisiplinan</th>
                            <th class="px-5 py-4">Kualitas</th>
                            <th class="px-5 py-4">Rata-rata</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($evaluations as $evaluation)
                            @php
                                $average = (
                                    $evaluation->skill_score +
                                    $evaluation->discipline_score +
                                    $evaluation->quality_score
                                ) / 3;
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $evaluations->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">
                                        {{ $evaluation->student?->name ?? 'Siswa tidak tersedia' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        ID: {{ $evaluation->student_id }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $evaluation->production?->product?->name ?? 'Produk tidak tersedia' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Produksi #{{ $evaluation->production_id }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700">
                                    {{ $evaluation->skill_score }}
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700">
                                    {{ $evaluation->discipline_score }}
                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700">
                                    {{ $evaluation->quality_score }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="font-bold {{ $average >= 75 ? 'text-green-700' : 'text-amber-600' }}">
                                        {{ number_format($average, 1, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-400">/ 100</span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.evaluations.show', $evaluation) }}"
                                            class="font-medium text-blue-600 hover:text-blue-800">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.evaluations.edit', $evaluation) }}"
                                            class="font-medium text-amber-600 hover:text-amber-800">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.evaluations.destroy', $evaluation) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus evaluasi ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="font-medium text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-600">
                                        Belum ada data evaluasi siswa.
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Tambahkan evaluasi untuk mulai mencatat hasil penilaian siswa.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($evaluations->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $evaluations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection