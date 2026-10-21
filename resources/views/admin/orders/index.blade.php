@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pesanan</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola dan pantau seluruh pesanan pelanggan TEFA.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col gap-3 md:flex-row">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor pesanan atau nama pelanggan..."
                    class="min-w-0 flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">

                <select name="status"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                    <option value="processing" @selected(request('status') === 'processing')>Diproses</option>
                    <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">
                    Cari
                </button>

                <a href="{{ route('admin.orders.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Reset
                </a>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">No.</th>
                            <th class="px-5 py-4 font-semibold">Nomor Pesanan</th>
                            <th class="px-5 py-4 font-semibold">Pelanggan</th>
                            <th class="px-5 py-4 font-semibold">Total</th>
                            <th class="px-5 py-4 font-semibold">Status Pesanan</th>
                            <th class="px-5 py-4 font-semibold">Pembayaran</th>
                            <th class="px-5 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $orders->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-gray-800">
                                    {{ $order->order_number }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $order->user->name ?? 'Pengguna tidak ditemukan' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $order->user->email ?? '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-800">
                                    Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'processing' => 'bg-purple-100 text-purple-700',
                                            'completed' => 'bg-green-100 text-green-700',
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
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    @if ($order->payment_status === 'paid')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                            Belum Dibayar
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="inline-flex items-center rounded-lg border border-green-200 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-50">
                                        Detail
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-600">
                                        Belum ada data pesanan.
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Pesanan pelanggan akan ditampilkan di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection