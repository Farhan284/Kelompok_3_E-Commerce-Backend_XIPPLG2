<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\Validator;

class laporanpenjualancontroller extends Controller
{
    public function index()
    {
        $laporan = LaporanPenjualan::with(['pesanan', 'pembayaran'])->get();

        return response()->json([
            'success' => true,
            'message' => 'List of Laporan Penjualan',
            'data' => $laporan
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'required|exists:pesanan,id',
            'id_pembayaran' => 'required|exists:pembayaran,id',
            'periode' => 'required|string|max:255',
            'total_penjualan' => 'required|numeric|min:0',
            'total_pendapatan' => 'required|numeric|min:0',
            'tanggal_laporan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $laporan = LaporanPenjualan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan created successfully',
            'data' => $laporan
        ], 201);
    }

    public function show($id)
    {
        $laporan = LaporanPenjualan::with(['pesanan', 'pembayaran'])->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan Penjualan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan found',
            'data' => $laporan
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan Penjualan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'sometimes|required|exists:pesanan,id',
            'id_pembayaran' => 'sometimes|required|exists:pembayaran,id',
            'periode' => 'sometimes|required|string|max:255',
            'total_penjualan' => 'sometimes|required|numeric|min:0',
            'total_pendapatan' => 'sometimes|required|numeric|min:0',
            'tanggal_laporan' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $laporan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan updated successfully',
            'data' => $laporan
        ], 200);
    }

    public function destroy($id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan Penjualan not found'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan deleted successfully'
        ], 200);
    }
}
