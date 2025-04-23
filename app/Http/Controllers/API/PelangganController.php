<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Pelanggan",
 *     description="Pelanggan"
 * )
 */
class pelanggancontroller extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pelanggan",
     *     tags={"Pelanggan"},
     *     summary="Menampilkan semua pelanggan",
     *     @OA\Response(
     *         response=200,
     *         description="List of Pelanggan",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $pelanggan = Pelanggan::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Pelanggan',
            'data' => $pelanggan
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/pelanggan",
     *     tags={"Pelanggan"},
     *     summary="Membuat pelanggan baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_pelanggan","email","password","alamat","nomor_telepon"},
     *             @OA\Property(property="nama_pelanggan", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="alamat", type="string"),
     *             @OA\Property(property="nomor_telepon", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pelanggan created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/pelanggan/{id}",
     *     tags={"Pelanggan"},
     *     summary="Menampilkan detail pelanggan berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pelanggan found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelanggan not found"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/pelanggan/{id}",
     *     tags={"Pelanggan"},
     *     summary="Mengupdate data pelanggan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_pelanggan", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="alamat", type="string"),
     *             @OA\Property(property="nomor_telepon", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pelanggan updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelanggan not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/pelanggan/{id}",
     *     tags={"Pelanggan"},
     *     summary="Menghapus pelanggan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pelanggan deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pelanggan not found"
     *     )
     * )
     */
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
