@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola dan pantau transaksi pembayaran pelanggan TEFA.
            </p>
        </div>

        {{-- Ringkasan --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:flex-wrap">
            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Pembayaran</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">
                    {{ number_format($totalPayments, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Berhasil</p>
                <p class="mt-2 text-2xl font-bold text-green-700">
                    {{ number_format($paidPayments, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Menunggu</p>
                <p class="mt-2 text-2xl font-bold text-yellow-600">
                    {{ number_format($pendingPayments, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Gagal</p>
                <p class="mt-2 text-2xl font-bold text-red-600">
                    {{ number_format($failedPayments, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="mt-2 text-xl font-bold text-green-700">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Pencarian dan filter --}}
        <form action="{{ route('admin.payments.index') }}" method="GET"
            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4">
                <div>
                    <label for="search" class="mb-1 block text-sm font-medium text-gray-700">
                        Cari pembayaran
                    </label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                        placeholder="ID pembayaran, nomor pesanan, nama pelanggan..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-green-600 focus:outline-none">
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
                    <div class="flex-1">
                        <label for="status" class="mb-1 block text-sm font-medium text-gray-700">
                            Status pembayaran
                        </label>
                        <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                            <option value="">Semua status</option>
                            <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                            <option value="paid" @selected(request('status') === 'paid')>Berhasil</option>
                            <option value="failed" @selected(request('status') === 'failed')>Gagal</option>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label for="date_from" class="mb-1 block text-sm font-medium text-gray-700">
                            Dari tanggal
                        </label>
                        <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div class="flex-1">
                        <label for="date_to" class="mb-1 block text-sm font-medium text-gray-700">
                            Sampai tanggal
                        </label>
                        <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                        class="rounded-lg bg-green-700 px-5 py-2.5 font-semibold text-white hover:bg-green-800">
                        Terapkan Filter
                    </button>

                    <a href="{{ route('admin.payments.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Tabel pembayaran --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4">Pembayaran</th>
                            <th class="px-5 py-4">Pelanggan</th>
                            <th class="px-5 py-4">Pesanan</th>
                            <th class="px-5 py-4">Nominal</th>
                            <th class="px-5 py-4">Metode</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($payments as $payment)
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];

                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'paid' => 'Berhasil',
                                    'failed' => 'Gagal',
                                ];
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">
                                        #{{ $payment->id }}
                                    </p>
                                    <p class="mt-1 max-w-48 truncate text-xs text-gray-500">
                                        {{ $payment->gateway_order_id }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    {{ $payment->order?->user?->name ?? 'Pengguna tidak tersedia' }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $payment->order?->order_number ?? '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $payment->payment_method ?: 'Belum diketahui' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $statusStyles[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $payment->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="font-semibold text-green-700 hover:text-green-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <p class="font-semibold text-gray-700">
                                        Belum ada data pembayaran
                                    </p>
                                    <p class="mt-1 text-gray-500">
                                        Data akan muncul setelah proses pembayaran dibuat.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-5 py-4">
                {{ $payments->links() }}
            </div>
        </div>

    </div>
@endsection