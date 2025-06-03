<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\PesananController;
use App\Http\Controllers\API\PembayaranController;
use App\Http\Controllers\API\LaporanPenjualanController;
use App\Http\Controllers\API\DetailPesananController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\StoreController;

// === Auth Routes ===
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// === Routes yang membutuhkan login ===
Route::middleware('auth:sanctum')->group(function () {
      Route::get('me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);



    Route::post('/pesanan/checkout', [PesananController::class, 'checkout']);
    Route::get('auth/roles', [AuthController::class, 'roles']);


    
    // Hanya user login yang bisa mengakses ini:
    Route::apiResource('user', UserController::class);
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('produk', ProdukController::class);
    Route::apiResource('pesanan', PesananController::class);
    Route::apiResource('pembayaran', PembayaranController::class);
    Route::apiResource('laporan-penjualan', LaporanPenjualanController::class);
    Route::apiResource('detail-pesanan', DetailPesananController::class);
    Route::apiResource('cart', CartController::class);
    Route::apiResource('review', ReviewController::class);
    Route::apiResource('store', StoreController::class);
    
   
});
