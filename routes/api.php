<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API bawaan untuk user yang sudah login
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API katalog produk TEFA
Route::get('/products', function () {
    return Product::with('category')
        ->where('status', 'active')
        ->latest()
        ->get();
});

// API detail produk TEFA
Route::get('/products/{product}', function (Product $product) {
    if ($product->status !== 'active') {
        return response()->json([
            'message' => 'Produk tidak tersedia.',
        ], 404);
    }

    return $product->load('category');
});
