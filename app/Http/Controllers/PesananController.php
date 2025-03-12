<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Validator;

class pesanancontroller extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('pelanggan')->get();

        return response()->json([
            'success' => true,
            'message' => 'List of Pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pelanggan' => 'required|exists:pelanggan,id',
            'tanggal_pesanan' => 'required|date',
            'status_pesanan' => 'required|string|max:255',
            'total_harga' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $pesanan = Pesanan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pesanan created successfully',
            'data' => $pesanan
        ], 201);
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('pelanggan')->find($id);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan found',
            'data' => $pesanan
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pelanggan' => 'sometimes|required|exists:pelanggan,id',
            'tanggal_pesanan' => 'sometimes|required|date',
            'status_pesanan' => 'sometimes|required|string|max:255',
            'total_harga' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $pesanan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pesanan updated successfully',
            'data' => $pesanan
        ], 200);
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan not found'
            ], 404);
        }

        $pesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan deleted successfully'
        ], 200);
    }
}
