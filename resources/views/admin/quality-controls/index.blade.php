@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Quality Control</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola pemeriksaan kualitas hasil produksi TEFA.
                </p>
            </div>

            <a href="{{ route('admin.quality-controls.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                + Tambah Pemeriksaan
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
            <form action="{{ route('admin.quality-controls.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama produk atau pemeriksa..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">

                <select name="status"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>
                        Menunggu Pemeriksaan
                    </option>
                    <option value="passed" @selected(request('status') === 'passed')>
                        Lulus
                    </option>
                    <option value="revision" @selected(request('status') === 'revision')>
                        Perlu Revisi
                    </option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-900">
                    Cari
                </button>

                <a href="{{ route('admin.quality-controls.index') }}"
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
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Pemeriksa</th>
                            <th class="px-5 py-4">Tanggal Pemeriksaan</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($qualityControls as $qc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $qualityControls->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">
                                        {{ $qc->production?->product?->name ?? 'Produk tidak tersedia' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Produksi #{{ $qc->production_id }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $qc->checkedBy?->name ?? 'Tidak diketahui' }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $qc->checked_at?->format('d M Y, H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($qc->status === 'passed')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Lulus
                                        </span>
                                    @elseif ($qc->status === 'revision')
                                        <span
                                            class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Perlu Revisi
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.quality-controls.show', $qc) }}"
                                            class="font-medium text-blue-600 hover:text-blue-800">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.quality-controls.edit', $qc) }}"
                                            class="font-medium text-amber-600 hover:text-amber-800">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.quality-controls.destroy', $qc) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data pemeriksaan ini?')">
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
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-600">
                                        Belum ada data Quality Control.
                                    </p>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Tambahkan pemeriksaan untuk mulai mengelola kualitas produksi.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($qualityControls->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $qualityControls->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection