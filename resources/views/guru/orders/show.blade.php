<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-2 text-[#222934]">
                Detail Pesanan
            </h2>

            <p class="tefa-p-14 mt-1 text-[#6D727E]">
                Informasi lengkap pesanan pelanggan.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex items-center gap-3">

                @if ($order->status === 'pending')

                    {{-- Setujui Pesanan --}}
                    <form action="{{ route('guru.orders.approve', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit" onclick="return confirm('Apakah kamu yakin ingin menyetujui pesanan ini?')"
                            class="inline-flex items-center rounded-lg bg-[#25764C] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#1F6040]">
                            Setujui Pesanan
                        </button>
                    </form>

                    {{-- Tolak Pesanan --}}
                    <form action="{{ route('guru.orders.reject', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit" onclick="return confirm('Apakah kamu yakin ingin menolak pesanan ini?')"
                            class="inline-flex items-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                            Tolak Pesanan
                        </button>
                    </form>

                @endif

                {{-- Kembali --}}
                <a href="{{ route('guru.orders.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>

            </div>

            {{-- Informasi Pesanan --}}
            <div class="mb-6 flex gap-6">

                {{-- Customer --}}
                <div class="flex-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <h4 class="text-base font-semibold text-[#222934]">
                        Informasi Pelanggan
                    </h4>

                    <div class="mt-4 space-y-3">

                        <div>
                            <p class="text-xs text-gray-500">
                                Nama
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-700">
                                {{ $order->user->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Email
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $order->user->email ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="flex-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <h4 class="text-base font-semibold text-[#222934]">
                        Status Pesanan
                    </h4>

                    <div class="mt-4">

                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'processing' => 'bg-purple-100 text-purple-700',
                                'completed' => 'bg-green-100 text-green-700',
                            ];

                            $statusLabels = [
                                'pending' => 'Menunggu Verifikasi',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'processing' => 'Diproses',
                                'completed' => 'Selesai',
                            ];
                        @endphp

                        <span
                            class="inline-flex rounded-full px-3 py-1.5 text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                        </span>

                        <div class="mt-4">
                            <p class="text-xs text-gray-500">
                                Status Pembayaran
                            </p>

                            @if ($order->payment_status === 'paid')

                                <span
                                    class="mt-1 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    Lunas
                                </span>

                            @else

                                <span
                                    class="mt-1 inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                    Belum Dibayar
                                </span>

                            @endif
                        </div>

                    </div>

                </div>

            </div>


            {{-- Daftar Produk --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-6 py-5">

                    <h4 class="text-base font-semibold text-[#222934]">
                        Produk yang Dipesan
                    </h4>

                    <p class="mt-1 text-sm text-gray-500">
                        Daftar produk yang terdapat dalam pesanan.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="border-b border-gray-200 bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Produk
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Harga
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Jumlah
                                </th>

                                <th class="px-6 py-4 text-right font-semibold text-gray-600">
                                    Subtotal
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse ($order->orderItems as $item)

                                <tr>

                                    <td class="px-6 py-4">

                                        <p class="font-medium text-gray-700">
                                            {{ $item->product->name ?? '-' }}
                                        </p>

                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-600">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-600">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="px-6 py-4 text-right font-medium text-gray-700">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                        Belum ada produk dalam pesanan ini.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Total --}}
                <div class="border-t border-gray-200 px-6 py-5">

                    <div class="flex items-center justify-end gap-8">

                        <span class="text-sm font-medium text-gray-500">
                            Total Pesanan
                        </span>

                        <span class="text-lg font-bold text-[#25764C]">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>