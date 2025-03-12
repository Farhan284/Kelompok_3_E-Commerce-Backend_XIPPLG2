<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;

class produkcontroller extends Controller
{
    public function index()
    {
        $produk = Produk::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Produk',
            'data' => $produk
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $produk = Produk::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk created successfully',
            'data' => $produk
        ], 201);
    }

    public function show($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk found',
            'data' => $produk
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'harga' => 'sometimes|required|numeric|min:0',
            'stok' => 'sometimes|required|integer|min:0',
            'id_kategori' => 'sometimes|required|exists:kategori,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $produk->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk updated successfully',
            'data' => $produk
        ], 200);
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk not found'
            ], 404);
        }

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk deleted successfully'
        ], 200);
    }
}
