@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Transaksi</h1>
            <p class="mt-1 text-sm text-gray-500">
                Ringkasan transaksi dan pembayaran pesanan TEFA.
            </p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ringkasan --}}
        <div class="flex flex-col gap-4 sm:flex-row">
            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">
                    {{ $totalTransactions }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Pembayaran Diterima</p>
                <p class="mt-2 text-xl font-bold text-green-700">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Transaksi Lunas</p>
                <p class="mt-2 text-2xl font-bold text-green-700">
                    {{ $paidTransactions }}
                </p>
            </div>

            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Belum Lunas</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">
                    {{ $unpaidTransactions }}
                </p>
            </div>
        </div>

        {{-- Pencarian dan Filter --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-col gap-3 lg:flex-row">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor transaksi atau nama pelanggan..."
                    class="min-w-0 flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none">

                <select name="status"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none">
                    <option value="">Semua Status Pesanan</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                    <option value="processing" @selected(request('status') === 'processing')>Diproses</option>
                    <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                </select>

                <select name="payment_status"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none">
                    <option value="">Semua Pembayaran</option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>Lunas</option>
                    <option value="unpaid" @selected(request('payment_status') === 'unpaid')>Belum Lunas</option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1D5E3C]">
                    Filter
                </button>

                <a href="{{ route('admin.transactions.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            </form>
        </div>

        {{-- Tabel Transaksi --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="font-semibold text-gray-800">Daftar Transaksi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Nomor Transaksi</th>
                            <th class="px-5 py-4">Pelanggan</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Total</th>
                            <th class="px-5 py-4">Status Pesanan</th>
                            <th class="px-5 py-4">Pembayaran</th>
                            <th class="px-5 py-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $transactions->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-800">
                                    {{ $transaction->order_number }}
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    {{ $transaction->user?->name ?? 'Pelanggan tidak tersedia' }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $transaction->created_at?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-gray-800">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'approved' => 'bg-blue-50 text-blue-700',
                                            'rejected' => 'bg-red-50 text-red-700',
                                            'processing' => 'bg-indigo-50 text-indigo-700',
                                            'completed' => 'bg-green-50 text-green-700',
                                        ];

                                        $statusLabels = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'processing' => 'Diproses',
                                            'completed' => 'Selesai',
                                        ];
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($transaction->payment_status === 'paid')
                                        <span
                                            class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            Lunas
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}"
                                        class="font-semibold text-green-700 hover:text-green-900">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-700">
                                        Belum ada transaksi.
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Transaksi akan muncul ketika data pesanan tersedia.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-5 py-4">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>
@endsection