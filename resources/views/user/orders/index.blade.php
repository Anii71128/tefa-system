@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 px-6 py-10">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Katalog Produk TEFA
                </h1>
                <p class="mt-2 text-gray-500">
                    Pilih produk yang ingin kamu pesan.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($products->isEmpty())
                <div class="rounded-xl border border-gray-200 bg-white p-10 text-center">
                    <h2 class="text-lg font-semibold text-gray-700">
                        Belum ada produk tersedia
                    </h2>
                    <p class="mt-2 text-gray-500">
                        Silakan kembali lagi nanti.
                    </p>
                </div>
            @else
                <form id="tefa-order-form" action="{{ route('user.orders.store') }}" method="POST">
                    @csrf

                    <div class="flex flex-col gap-6">
                        @foreach ($products as $product)
                            <div data-product-row
                                class="flex flex-col gap-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center">
                                <div class="flex-1">
                                    <h2 class="text-lg font-semibold text-gray-800">
                                        {{ $product->name }}
                                    </h2>

                                    <p class="mt-2 text-sm text-gray-500">
                                        {{ $product->description ?: 'Tidak ada deskripsi produk.' }}
                                    </p>

                                    <p class="mt-3 font-semibold text-green-700">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Stok tersedia: {{ $product->stock }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <label for="quantity-{{ $product->id }}" class="text-sm text-gray-600">
                                        Jumlah
                                    </label>

                                    <input id="quantity-{{ $product->id }}" type="number" name="items[{{ $product->id }}][quantity]"
                                        min="1" max="{{ min($product->stock, 100) }}" value="1"
                                        class="w-24 rounded-lg border border-gray-300 px-3 py-2">

                                    <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="rounded-lg bg-green-700 px-6 py-3 font-semibold text-white transition hover:bg-green-800">
                            Buat Pesanan
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#tefa-order-form');

        if (!form) {
            return;
        }

        form.addEventListener('submit', function (event) {
            const productRows = form.querySelectorAll('[data-product-row]');
            let selectedCount = 0;

            productRows.forEach(function (row) {
                const quantityInput = row.querySelector(
                    'input[name$="[quantity]"]'
                );

                const productIdInput = row.querySelector(
                    'input[name$="[product_id]"]'
                );

                const quantity = Number(quantityInput.value);

                if (quantity === 0) {
                    // Produk dengan jumlah 0 tidak dikirim ke server.
                    quantityInput.disabled = true;
                    productIdInput.disabled = true;
                } else {
                    selectedCount++;
                }
            });

            if (selectedCount === 0) {
                event.preventDefault();

                // Aktifkan kembali input agar pelanggan bisa memilih produk.
                productRows.forEach(function (row) {
                    row.querySelectorAll('input').forEach(function (input) {
                        input.disabled = false;
                    });
                });

                alert('Pilih minimal satu produk dengan jumlah lebih dari 0.');
            }
        });
    });
</script>
