@extends('admin.layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div>
        <a href="{{ route('admin.productions.index') }}"
            class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
            ← Kembali ke Produksi
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">
            Tambah Produksi
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Isi informasi produksi Teaching Factory di bawah ini.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="mb-2 font-semibold">Periksa kembali data berikut:</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.productions.store') }}" method="POST"
        class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            <div class="sm:col-span-2">
                <label for="product_id" class="mb-2 block text-sm font-semibold text-gray-700">
                    Produk <span class="text-red-500">*</span>
                </label>

                <select id="product_id" name="product_id" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="">-- Pilih Produk --</option>

                    @forelse ($products as $product)
                        <option value="{{ $product->id }}"
                            @selected(old('product_id') == $product->id)>
                            {{ $product->name }}
                        </option>
                    @empty
                        <option value="" disabled>Belum ada produk</option>
                    @endforelse
                </select>

                @if ($products->isEmpty())
                    <p class="mt-2 text-xs text-amber-600">
                        Belum ada produk. Tambahkan produk terlebih dahulu melalui menu Produk.
                    </p>
                @endif
            </div>

            <div class="sm:col-span-2">
                <label for="guru_id" class="mb-2 block text-sm font-semibold text-gray-700">
                    Guru Penanggung Jawab <span class="text-red-500">*</span>
                </label>

                <select id="guru_id" name="guru_id" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="">-- Pilih Guru --</option>

                    @forelse ($gurus as $guru)
                        <option value="{{ $guru->id }}"
                            @selected(old('guru_id') == $guru->id)>
                            {{ $guru->name }}
                        </option>
                    @empty
                        <option value="" disabled>Belum ada akun guru</option>
                    @endforelse
                </select>

                @if ($gurus->isEmpty())
                    <p class="mt-2 text-xs text-amber-600">
                        Belum ada akun dengan role guru. Periksa data pengguna terlebih dahulu.
                    </p>
                @endif
            </div>

            <div>
                <label for="quantity" class="mb-2 block text-sm font-semibold text-gray-700">
                    Jumlah Produksi <span class="text-red-500">*</span>
                </label>

                <input type="number" id="quantity" name="quantity"
                    value="{{ old('quantity', 1) }}" min="1" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    placeholder="Contoh: 50">
            </div>

            <div>
                <label for="production_date" class="mb-2 block text-sm font-semibold text-gray-700">
                    Tanggal Produksi <span class="text-red-500">*</span>
                </label>

                <input type="date" id="production_date" name="production_date"
                    value="{{ old('production_date', now()->format('Y-m-d')) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                    Status Produksi <span class="text-red-500">*</span>
                </label>

                <select id="status" name="status" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="planned" @selected(old('status', 'planned') === 'planned')>
                        Direncanakan
                    </option>
                    <option value="in_progress" @selected(old('status') === 'in_progress')>
                        Sedang Berjalan
                    </option>
                    <option value="qc" @selected(old('status') === 'qc')>
                        Quality Control
                    </option>
                    <option value="revision" @selected(old('status') === 'revision')>
                        Revisi
                    </option>
                    <option value="completed" @selected(old('status') === 'completed')>
                        Selesai
                    </option>
                </select>
            </div>

            <div>
                <label for="progress" class="mb-2 block text-sm font-semibold text-gray-700">
                    Progres Produksi (%) <span class="text-red-500">*</span>
                </label>

                <input type="number" id="progress" name="progress"
                    value="{{ old('progress', 0) }}" min="0" max="100" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    placeholder="0–100">
                <p class="mt-1 text-xs text-gray-500">
                    Masukkan angka dari 0 sampai 100.
                </p>
            </div>

            <div class="sm:col-span-2">
                <label for="notes" class="mb-2 block text-sm font-semibold text-gray-700">
                    Catatan Produksi
                </label>

                <textarea id="notes" name="notes" rows="4"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                    placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.productions.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                @disabled($products->isEmpty() || $gurus->isEmpty())
                class="rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                Simpan Produksi
            </button>
        </div>
    </form>
</div>
@endsection
