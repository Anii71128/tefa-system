<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Produk
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola data produk Teaching Factory.
                </p>
            </div>

            <a href="{{ route('guru.products.create') }}"
                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-700">
                                    Produk
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-700">
                                    Harga
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-700">
                                    Stok
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-700">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right font-semibold text-gray-700">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">
                                            {{ $product->name }}
                                        </div>

                                        @if ($product->description)
                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ Str::limit($product->description, 60) }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($product->status === 'active')
                                            <span
                                                class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">

                                            <a href="{{ route('guru.products.show', $product) }}"
                                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">
                                                Detail
                                            </a>

                                            <a href="{{ route('guru.products.edit', $product) }}"
                                                class="rounded-lg bg-green-50 px-3 py-2 text-xs font-medium text-green-700 hover:bg-green-100">
                                                Edit
                                            </a>

                                        </div>
                                    </td>

                                </tr>
                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">

                                        <div class="text-gray-400">
                                            Belum ada data produk.
                                        </div>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Tambahkan produk melalui tombol
                                            <span class="font-medium">Tambah Produk</span>.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>