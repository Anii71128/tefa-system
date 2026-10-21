@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Header --}}
        <div>
            <a href="{{ route('admin.transactions.index') }}"
                class="text-sm font-medium text-green-700 hover:text-green-900">
                ← Kembali ke Transaksi
            </a>

            <div class="mt-3">
                <h1 class="text-2xl font-bold text-gray-800">
                    Detail Transaksi
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Rincian pesanan dan pembayaran pelanggan TEFA.
                </p>
            </div>
        </div>

        {{-- Informasi Transaksi --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div
                class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Nomor Transaksi</p>
                    <h2 class="mt-1 text-lg font-bold text-gray-800">
                        {{ $transaction->order_number }}
                    </h2>
                </div>

                <p class="text-sm text-gray-500">
                    {{ $transaction->created_at?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            <div class="space-y-6 p-6">

                {{-- Informasi Pelanggan --}}
                <section>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Informasi Pelanggan
                    </h3>

                    <div class="flex flex-col gap-5 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Nama Pelanggan</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $transaction->user?->name ?? 'Pelanggan tidak tersedia' }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $transaction->user?->email ?? '-' }}
                            </p>
                        </div>
                    </div>
                </section>

                <div class="border-t border-gray-100"></div>

                {{-- Status --}}
                <section>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Status Transaksi
                    </h3>

                    <div class="flex flex-col gap-5 sm:flex-row">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Status Pesanan</p>
                            @php
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'processing' => 'Diproses',
                                    'completed' => 'Selesai',
                                ];

                                $statusClasses = [
                                    'pending' => 'bg-amber-50 text-amber-700',
                                    'approved' => 'bg-blue-50 text-blue-700',
                                    'rejected' => 'bg-red-50 text-red-700',
                                    'processing' => 'bg-indigo-50 text-indigo-700',
                                    'completed' => 'bg-green-50 text-green-700',
                                ];
                            @endphp

                            <span
                                class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                            </span>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Status Pembayaran</p>

                            @if ($transaction->payment_status === 'paid')
                                <span
                                    class="mt-2 inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                    Lunas
                                </span>
                            @else
                                <span
                                    class="mt-2 inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                    Belum Lunas
                                </span>
                            @endif
                        </div>
                    </div>
                </section>

                <div class="border-t border-gray-100"></div>

                {{-- Daftar Produk --}}
                <section>
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">
                        Rincian Produk
                    </h3>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full min-w-[600px] text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="px-4 py-3">Harga</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Subtotal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse ($transaction->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-4 font-medium text-gray-800">
                                            {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-4 text-gray-600">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-4 py-4 font-semibold text-gray-800">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                            Belum ada rincian produk untuk transaksi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <div class="w-full rounded-lg bg-green-50 p-5 sm:max-w-sm">
                            <p class="text-sm text-green-800">Total Transaksi</p>
                            <p class="mt-1 text-2xl font-bold text-green-700">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </section>

            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                <a href="{{ route('admin.transactions.index') }}"
                    class="inline-flex rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Kembali ke Daftar Transaksi
                </a>
            </div>

        </div>
    </div>
@endsection