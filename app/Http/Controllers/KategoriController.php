<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;

class kategoricontroller extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Kategori',
            'data' => $kategori
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori = Kategori::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori created successfully',
            'data' => $kategori
        ], 201);
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kategori found',
            'data' => $kategori
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:255|unique:kategori,nama_kategori,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori updated successfully',
            'data' => $kategori
        ], 200);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori deleted successfully'
        ], 200);
    }
}
