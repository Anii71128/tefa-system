@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.payments.index') }}"
                    class="text-sm font-medium text-green-700 hover:text-green-900">
                    &larr; Kembali ke Pembayaran
                </a>

                <h1 class="mt-3 text-2xl font-bold text-gray-800">
                    Detail Pembayaran
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi pembayaran dan pesanan pelanggan TEFA.
                </p>
            </div>

            @php
                $statusStyles = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'paid' => 'bg-green-100 text-green-800',
                    'failed' => 'bg-red-100 text-red-800',
                ];

                $statusLabels = [
                    'pending' => 'Menunggu Pembayaran',
                    'paid' => 'Berhasil / Lunas',
                    'failed' => 'Gagal',
                ];
            @endphp

            <span class="w-fit rounded-full px-4 py-2 text-sm font-semibold
                {{ $statusStyles[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                {{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}
            </span>
        </div>

        {{-- Ringkasan nominal --}}
        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">Nominal Pembayaran</p>

            <h2 class="mt-2 text-3xl font-bold text-green-700">
                Rp {{ number_format($payment->amount, 0, ',', '.') }}
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Gateway: {{ strtoupper($payment->gateway) }}
            </p>
        </section>

        <div class="flex flex-col gap-6 lg:flex-row">

            {{-- Informasi pembayaran --}}
            <section class="flex-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800">
                    Informasi Pembayaran
                </h2>

                <dl class="mt-5 flex flex-col gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">ID Pembayaran</dt>
                        <dd class="mt-1 break-all font-medium text-gray-800">
                            {{ $payment->gateway_order_id }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">ID Transaksi Gateway</dt>
                        <dd class="mt-1 break-all font-medium text-gray-800">
                            {{ $payment->gateway_transaction_id ?: 'Belum tersedia' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Metode Pembayaran</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $payment->payment_method ?: 'Belum diketahui' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Dibuat pada</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $payment->created_at?->format('d M Y, H:i:s') ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Waktu Pembayaran Berhasil</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $payment->paid_at?->format('d M Y, H:i:s') ?? 'Belum dibayar' }}
                        </dd>
                    </div>
                </dl>
            </section>

            {{-- Informasi pelanggan --}}
            <section class="flex-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800">
                    Informasi Pelanggan
                </h2>

                <dl class="mt-5 flex flex-col gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nama</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ $payment->order?->user?->name ?? 'Pengguna tidak tersedia' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="mt-1 break-all font-medium text-gray-800">
                            {{ $payment->order?->user?->email ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Nomor Pesanan</dt>
                        <dd class="mt-1 break-all font-medium text-gray-800">
                            {{ $payment->order?->order_number ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Status Pesanan</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ ucfirst($payment->order?->status ?? 'unknown') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Status Pembayaran Pesanan</dt>
                        <dd class="mt-1 font-medium text-gray-800">
                            {{ ucfirst($payment->order?->payment_status ?? 'unknown') }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>

        {{-- Rincian produk --}}
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800">
                    Rincian Produk Pesanan
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Harga</th>
                            <th class="px-5 py-4">Jumlah</th>
                            <th class="px-5 py-4">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($payment->order?->orderItems ?? [] as $item)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $item->quantity }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-semibold">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                    Tidak ada rincian produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between border-t border-gray-100 px-5 py-5">
                <span class="font-semibold text-gray-700">Total Pesanan</span>
                <span class="font-bold text-gray-900">
                    Rp {{ number_format($payment->order?->total_amount ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </section>

        <p class="text-sm text-gray-500">
            Status pembayaran berasal dari proses verifikasi backend dan notifikasi gateway.
            Halaman ini hanya menampilkan informasi, bukan mengubah status transaksi.
        </p>

    </div>
@endsection