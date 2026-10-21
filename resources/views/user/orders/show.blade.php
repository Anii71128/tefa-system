@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 px-6 py-10">
        <div class="mx-auto max-w-5xl">

            <a href="{{ route('user.orders.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-green-700 hover:text-green-900">
                &larr; Kembali ke Katalog
            </a>

            <div class="mb-8 mt-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    Detail Pesanan
                </h1>
                <p class="mt-2 text-gray-500">
                    Periksa detail pesanan dan status transaksi kamu.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col gap-6">

                {{-- Informasi pesanan --}}
                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Nomor Pesanan</p>
                            <h2 class="mt-1 break-all text-lg font-bold text-gray-800">
                                {{ $order->order_number }}
                            </h2>

                            <p class="mt-3 text-sm text-gray-500">
                                Dibuat pada
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @php
                                $orderStatus = [
                                    'pending' => ['Menunggu Persetujuan', 'bg-yellow-100 text-yellow-800'],
                                    'approved' => ['Disetujui', 'bg-green-100 text-green-800'],
                                    'rejected' => ['Ditolak', 'bg-red-100 text-red-800'],
                                    'processing' => ['Diproses', 'bg-blue-100 text-blue-800'],
                                    'completed' => ['Selesai', 'bg-gray-100 text-gray-800'],
                                ];

                                $paymentStatus = [
                                    'unpaid' => ['Belum Dibayar', 'bg-yellow-100 text-yellow-800'],
                                    'paid' => ['Lunas', 'bg-green-100 text-green-800'],
                                ];

                                $currentOrderStatus = $orderStatus[$order->status]
                                    ?? ['Tidak diketahui', 'bg-gray-100 text-gray-800'];

                                $currentPaymentStatus = $paymentStatus[$order->payment_status]
                                    ?? ['Tidak diketahui', 'bg-gray-100 text-gray-800'];
                            @endphp

                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $currentOrderStatus[1] }}">
                                {{ $currentOrderStatus[0] }}
                            </span>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $currentPaymentStatus[1] }}">
                                {{ $currentPaymentStatus[0] }}
                            </span>
                        </div>
                    </div>
                </section>

                {{-- Daftar produk --}}
                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-800">
                        Produk yang Dipesan
                    </h2>

                    <div class="mt-5 flex flex-col divide-y divide-gray-100">
                        @forelse ($order->orderItems as $item)
                            <div class="flex flex-col gap-2 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $item->quantity }} ×
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <p class="font-semibold text-gray-800">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @empty
                            <p class="py-4 text-sm text-gray-500">
                                Belum ada produk dalam pesanan ini.
                            </p>
                        @endforelse
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-5">
                        <span class="font-semibold text-gray-700">
                            Total Pesanan
                        </span>

                        <span class="text-xl font-bold text-green-700">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </section>

                {{-- Riwayat pembayaran --}}
                <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-800">
                        Informasi Pembayaran
                    </h2>

                    @forelse ($order->payments->sortByDesc('created_at') as $payment)
                        <div class="mt-4 rounded-lg border border-gray-100 p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ strtoupper($payment->gateway) }}
                                    </p>

                                    <p class="mt-1 break-all text-sm text-gray-500">
                                        ID: {{ $payment->gateway_order_id }}
                                    </p>
                                </div>

                                <div class="sm:text-right">
                                    <p class="font-semibold text-gray-800">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Status: {{ ucfirst($payment->status) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="mt-3 text-sm text-gray-500">
                            Belum ada transaksi pembayaran.
                        </p>
                    @endforelse

                    @if ($order->status === 'pending')
                        <div class="mt-5 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800">
                            Pesanan sedang menunggu persetujuan guru.
                            Pembayaran akan tersedia setelah pesanan disetujui.
                        </div>
                    @elseif ($order->status === 'rejected')
                        <div class="mt-5 rounded-lg bg-red-50 p-4 text-sm text-red-800">
                            Pesanan ditolak. Silakan hubungi pihak TEFA jika membutuhkan informasi lebih lanjut.
                        </div>
                    @elseif ($order->status === 'approved' && $order->payment_status !== 'paid')
                        <div class="mt-5 rounded-lg bg-green-50 p-4">
                            <p class="text-sm text-green-800">
                                Pesanan sudah disetujui. Silakan lanjutkan pembayaran.
                            </p>

                            <button id="pay-button" type="button"
                                class="mt-4 rounded-lg bg-green-700 px-5 py-3 font-semibold text-white hover:bg-green-800 disabled:opacity-50">
                                Bayar Sekarang
                            </button>

                            <p id="payment-message" class="mt-3 text-sm text-gray-600"></p>
                        </div>
                    @elseif ($order->payment_status === 'paid')
                        <div class="mt-5 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                            Pembayaran telah tercatat lunas.
                        </div>
                    @endif

                </section>

            </div>
        </div>
    </div>
    @if ($order->status === 'approved' && $order->payment_status !== 'paid')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const button = document.getElementById('pay-button');
                const message = document.getElementById('payment-message');

                if (!button) return;

                button.addEventListener('click', async function () {
                    button.disabled = true;
                    message.textContent = 'Menyiapkan pembayaran...';

                    try {
                        const response = await fetch(
                            @json(route('orders.payment.create', $order)),
                            {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]'
                                    ).content
                                },
                                body: JSON.stringify({})
                            }
                        );

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Gagal menyiapkan pembayaran.');
                        }

                        if (!window.snap) {
                            throw new Error('Midtrans Snap belum berhasil dimuat. Periksa koneksi internet.');
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function () {
                                message.textContent =
                                    'Pembayaran berhasil diproses. Menunggu konfirmasi status dari Midtrans.';
                                window.location.reload();
                            },
                            onPending: function () {
                                message.textContent =
                                    'Pembayaran masih menunggu penyelesaian.';
                                button.disabled = false;
                            },
                            onError: function () {
                                message.textContent =
                                    'Pembayaran gagal. Silakan coba kembali.';
                                button.disabled = false;
                            },
                            onClose: function () {
                                message.textContent =
                                    'Jendela pembayaran ditutup. Kamu bisa mencoba kembali.';
                                button.disabled = false;
                            }
                        });
                    } catch (error) {
                        message.textContent = error.message;
                        button.disabled = false;
                    }
                });
            });
        </script>
    @endif
@endsection