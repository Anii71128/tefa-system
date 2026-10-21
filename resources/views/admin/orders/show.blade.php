@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Nomor Pesanan: {{ $order->order_number }}
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-600 hover:bg-gray-50">
                Kembali
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

        <div class="flex flex-col gap-6 lg:flex-row">

            {{-- Informasi pelanggan --}}
            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Informasi Pelanggan
                </h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Pelanggan</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $order->user->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $order->user->email ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Total Pesanan</p>
                        <p class="mt-1 text-xl font-bold text-green-700">
                            Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ubah status --}}
            <div class="flex-1 rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">
                    Kelola Status
                </h2>

                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                            Status Pesanan
                        </label>

                        <select id="status" name="status" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                            <option value="pending" @selected(old('status', $order->status) === 'pending')>
                                Menunggu
                            </option>
                            <option value="approved" @selected(old('status', $order->status) === 'approved')>
                                Disetujui
                            </option>
                            <option value="rejected" @selected(old('status', $order->status) === 'rejected')>
                                Ditolak
                            </option>
                            <option value="processing" @selected(old('status', $order->status) === 'processing')>
                                Diproses
                            </option>
                            <option value="completed" @selected(old('status', $order->status) === 'completed')>
                                Selesai
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_status" class="mb-2 block text-sm font-medium text-gray-700">
                            Status Pembayaran
                        </label>

                        <select id="payment_status" name="payment_status" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-1 focus:ring-green-600">
                            <option value="unpaid" @selected(old('payment_status', $order->payment_status) === 'unpaid')>
                                Belum Dibayar
                            </option>
                            <option value="paid" @selected(old('payment_status', $order->payment_status) === 'paid')>
                                Lunas
                            </option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-green-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-800">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar produk --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Produk yang Dipesan</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 font-semibold">Harga</th>
                            <th class="px-5 py-4 font-semibold">Jumlah</th>
                            <th class="px-5 py-4 font-semibold">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($order->orderItems as $item)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    Rp{{ number_format((float) $item->price, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $item->quantity }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-800">
                                    Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                                    Belum ada rincian produk untuk pesanan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection