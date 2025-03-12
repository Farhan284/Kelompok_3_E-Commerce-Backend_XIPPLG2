<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\DetailPesananController;

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
