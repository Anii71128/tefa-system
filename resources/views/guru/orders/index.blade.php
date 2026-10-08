<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="tefa-heading-2 text-[#222934]">
                Pesanan
            </h2>

            <p class="tefa-p-14 mt-1 text-[#6D727E]">
                Kelola dan verifikasi pesanan pelanggan.
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

            {{-- Header --}}
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#222934]">
                        Daftar Pesanan
                    </h3>

                    <p class="mt-1 text-sm text-[#6D727E]">
                        Daftar pesanan yang masuk dari pelanggan.
                    </p>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">

                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    No. Pesanan
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Pelanggan
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Total
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Status
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Pembayaran
                                </th>

                                <th class="px-6 py-4 text-center font-semibold text-gray-600">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($orders as $order)

                                <tr class="hover:bg-gray-50">

                                    {{-- Order Number --}}
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-[#222934]">
                                            {{ $order->order_number }}
                                        </span>

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $order->created_at->format('d M Y') }}
                                        </p>
                                    </td>

                                    {{-- User --}}
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-700">
                                            {{ $order->user->name ?? '-' }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            {{ $order->user->email ?? '-' }}
                                        </p>
                                    </td>

                                    {{-- Total --}}
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">

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
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                        </span>

                                    </td>

                                    {{-- Payment --}}
                                    <td class="px-6 py-4">

                                        @if ($order->payment_status === 'paid')

                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                Lunas
                                            </span>

                                        @else

                                            <span
                                                class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                                Belum Dibayar
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Action --}}
                                    <td class="px-6 py-4 text-center">

                                        <a href="{{ route('guru.orders.show', $order) }}"
                                            class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                            Detail
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-sm text-gray-500">
                                            Belum ada pesanan.
                                        </div>

                                        <p class="mt-1 text-xs text-gray-400">
                                            Pesanan pelanggan akan muncul di halaman ini.
                                        </p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if ($orders->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $orders->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>