<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class pelanggancontroller extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Pelanggan',
            'data' => $pelanggan
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pelanggan,email',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $pelanggan = Pelanggan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan created successfully',
            'data' => $pelanggan
        ], 201);
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan found',
            'data' => $pelanggan
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:pelanggan,email,' . $id . ',id',
            'password' => 'sometimes|required|string|min:6',
            'alamat' => 'sometimes|required|string',
            'nomor_telepon' => 'sometimes|required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $pelanggan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan updated successfully',
            'data' => $pelanggan
        ], 200);
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan not found'
            ], 404);
        }

        $pelanggan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan deleted successfully'
        ], 200);
    }
}
