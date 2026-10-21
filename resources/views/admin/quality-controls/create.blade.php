@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        <div>
            <a href="{{ route('admin.quality-controls.index') }}"
                class="text-sm font-medium text-green-700 hover:text-green-900">
                ← Kembali ke Quality Control
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Tambah Pemeriksaan QC
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Isi informasi pemeriksaan kualitas hasil produksi.
            </p>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.quality-controls.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="production_id" class="mb-2 block text-sm font-semibold text-gray-700">
                        Produksi <span class="text-red-500">*</span>
                    </label>

                    <select name="production_id" id="production_id" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                        <option value="">-- Pilih Produksi --</option>

                        @foreach ($productions as $production)
                            <option value="{{ $production->id }}" @selected(old('production_id') == $production->id)>
                                Produksi #{{ $production->id }} —
                                {{ $production->product?->name ?? 'Produk tidak tersedia' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="checked_by" class="mb-2 block text-sm font-semibold text-gray-700">
                        Petugas Pemeriksa <span class="text-red-500">*</span>
                    </label>

                    <select name="checked_by" id="checked_by" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                        <option value="">-- Pilih Petugas --</option>

                        @foreach ($inspectors as $inspector)
                            <option value="{{ $inspector->id }}" @selected(old('checked_by') == $inspector->id)>
                                {{ $inspector->name }}
                                ({{ ucfirst($inspector->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                        Status Pemeriksaan <span class="text-red-500">*</span>
                    </label>

                    <select name="status" id="status" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                        <option value="pending" @selected(old('status', 'pending') === 'pending')>
                            Menunggu Pemeriksaan
                        </option>
                        <option value="passed" @selected(old('status') === 'passed')>
                            Lulus QC
                        </option>
                        <option value="revision" @selected(old('status') === 'revision')>
                            Perlu Revisi
                        </option>
                    </select>
                </div>

                <div>
                    <label for="checked_at" class="mb-2 block text-sm font-semibold text-gray-700">
                        Tanggal dan Waktu Pemeriksaan
                    </label>

                    <input type="datetime-local" name="checked_at" id="checked_at" value="{{ old('checked_at') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">

                    <p class="mt-1 text-xs text-gray-500">
                        Boleh dikosongkan jika pemeriksaan belum dilakukan.
                    </p>
                </div>

                <div>
                    <label for="notes" class="mb-2 block text-sm font-semibold text-gray-700">
                        Catatan Pemeriksaan
                    </label>

                    <textarea name="notes" id="notes" rows="4"
                        placeholder="Tuliskan hasil pemeriksaan atau catatan revisi..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">{{ old('notes') }}</textarea>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.quality-controls.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                        Simpan Pemeriksaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection