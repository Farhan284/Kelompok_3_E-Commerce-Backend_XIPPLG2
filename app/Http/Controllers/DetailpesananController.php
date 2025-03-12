<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Validator;

class detailpesananController extends Controller
{
    public function index()
    {
        $details = DetailPesanan::all();
        
        return response()->json([
            'success' => true,
            'message' => 'List of Detail Pesanan',
            'data' => $details
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'required|exists:pesanan,id',
            'id_produk' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $detailPesanan = DetailPesanan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan created successfully',
            'data' => $detailPesanan
        ], 201);
    }

    public function show($id)
    {
        $detailPesanan = DetailPesanan::find($id);

        if (!$detailPesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Pesanan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan found',
            'data' => $detailPesanan
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $detailPesanan = DetailPesanan::find($id);

        if (!$detailPesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Pesanan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jumlah' => 'sometimes|required|integer|min:1',
            'subtotal' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $detailPesanan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan updated successfully',
            'data' => $detailPesanan
        ], 200);
    }

    public function destroy($id)
    {
        $detailPesanan = DetailPesanan::find($id);

        if (!$detailPesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Pesanan not found'
            ], 404);
        }

        $detailPesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan deleted successfully'
        ], 200);
    }
}
