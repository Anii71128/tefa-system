@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <a href="{{ route('admin.production-students.index') }}"
                    class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                    ← Kembali ke Penugasan Siswa
                </a>

                <h1 class="mt-3 text-2xl font-bold text-gray-800">
                    Detail Penugasan Siswa
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap tugas siswa dalam kegiatan produksi TEFA.
                </p>
            </div>

            <a href="{{ route('admin.production-students.edit', $productionStudent) }}"
                class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                Edit Penugasan
            </a>
        </div>

        {{-- Status --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <p class="text-sm text-gray-500">Nomor Penugasan</p>
                    <h2 class="mt-1 text-xl font-bold text-gray-800">
                        #{{ $productionStudent->id }}
                    </h2>
                </div>

                <div>
                    @if ($productionStudent->status === 'assigned')
                        <span class="inline-flex rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                            Ditugaskan
                        </span>
                    @elseif ($productionStudent->status === 'in_progress')
                        <span class="inline-flex rounded-full bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                            Sedang dikerjakan
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                            Selesai
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informasi produksi --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="font-semibold text-gray-800">
                    Informasi Produksi
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Nomor Produksi</p>
                    <p class="mt-1 font-medium text-gray-800">
                        #{{ $productionStudent->production_id }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Nama Produk</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $productionStudent->production?->product?->name ?? 'Produk tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Jumlah Produksi</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $productionStudent->production?->quantity ?? '-' }} unit
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Produksi</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $productionStudent->production?->production_date?->format('d/m/Y') ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Informasi siswa dan tugas --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="font-semibold text-gray-800">
                    Informasi Penugasan
                </h2>
            </div>

            <div class="space-y-6 p-6">
                <div>
                    <p class="text-sm text-gray-500">Nama Siswa</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $productionStudent->student?->name ?? 'Siswa tidak tersedia' }}
                    </p>

                    @if ($productionStudent->student)
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $productionStudent->student->email }}
                        </p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500">Deskripsi Tugas</p>
                    <div class="mt-2 whitespace-pre-line rounded-lg bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                        {{ $productionStudent->task }}
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tenggat Penyelesaian</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $productionStudent->deadline?->format('d F Y') ?? 'Belum ditentukan' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Dibuat Pada</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $productionStudent->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Terakhir Diperbarui</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $productionStudent->updated_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aksi hapus --}}
        <div class="flex justify-end">
            <form action="{{ route('admin.production-students.destroy', $productionStudent) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus penugasan ini?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                    class="rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    Hapus Penugasan
                </button>
            </form>
        </div>

    </div>
@endsection