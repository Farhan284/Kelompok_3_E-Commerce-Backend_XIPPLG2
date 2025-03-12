<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Validator;

class pembayarancontroller extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['pesanan', 'pelanggan'])->get();

        return response()->json([
            'success' => true,
            'message' => 'List of Pembayaran',
            'data' => $pembayaran
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'required|exists:pesanan,id',
            'id_pelanggan' => 'required|exists:pelanggan,id',
            'metode_pembayaran' => 'required|string|max:255',
            'status_pembayaran' => 'required|string|max:255',
            'tanggal_pembayaran' => 'required|date',
            'total_pembayaran' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $pembayaran = Pembayaran::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran created successfully',
            'data' => $pembayaran
        ], 201);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['pesanan', 'pelanggan'])->find($id);

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran found',
            'data' => $pembayaran
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'sometimes|required|exists:pesanan,id',
            'id_pelanggan' => 'sometimes|required|exists:pelanggan,id',
            'metode_pembayaran' => 'sometimes|required|string|max:255',
            'status_pembayaran' => 'sometimes|required|string|max:255',
            'tanggal_pembayaran' => 'sometimes|required|date',
            'total_pembayaran' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $pembayaran->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran updated successfully',
            'data' => $pembayaran
        ], 200);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::find($id);

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran not found'
            ], 404);
        }

        $pembayaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran deleted successfully'
        ], 200);
    }
}
