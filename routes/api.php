<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PelangganController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\PesananController;
use App\Http\Controllers\API\PembayaranController;
use App\Http\Controllers\API\LaporanPenjualanController;
use App\Http\Controllers\API\DetailPesananController;

Route::apiResource('pelanggan', PelangganController::class);
Route::apiResource('kategori', KategoriController::class);
Route::apiResource('produk', ProdukController::class);
Route::apiResource('pesanan', PesananController::class);
Route::apiResource('pembayaran', PembayaranController::class);
Route::apiResource('laporan-penjualan', LaporanPenjualanController::class);
Route::apiResource('detail-pesanan', DetailPesananController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
