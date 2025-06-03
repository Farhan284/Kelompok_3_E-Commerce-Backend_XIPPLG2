<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Produk",
 *     description="Manajemen Produk"
 * )
 */
class ProdukController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/produk",
     *     tags={"Produk"},
     *     summary="Menampilkan semua produk",
     * tags={"produk"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of Produk",
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
        $produk = Produk::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Produk',
            'data' => $produk
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/produk",
     *     tags={"Produk"},
     *     summary="Membuat produk baru",
     * tags={"produk"},
 *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_produk","deskripsi","harga","stok","id_kategori"},
     *             @OA\Property(property="nama_produk", type="string"),
     *             @OA\Property(property="deskripsi", type="string"),
     *             @OA\Property(property="harga", type="number", format="float"),
     *             @OA\Property(property="stok", type="integer"),
     *             @OA\Property(property="id_kategori", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk created successfully"
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

    /**
     * @OA\Get(
     *     path="/api/produk/{id}",
     *     tags={"Produk"},
     *     summary="Menampilkan detail produk berdasarkan ID",
     * tags={"produk"},
 *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk not found"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/produk/{id}",
     *     tags={"Produk"},
     *     summary="Mengupdate data produk",
     * tags={"produk"},
 *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_produk", type="string"),
     *             @OA\Property(property="deskripsi", type="string"),
     *             @OA\Property(property="harga", type="number", format="float"),
     *             @OA\Property(property="stok", type="integer"),
     *             @OA\Property(property="id_kategori", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/produk/{id}",
     *     tags={"Produk"},
     *     summary="Menghapus produk",
     * tags={"produk"},
 *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk not found"
     *     )
     * )
     */
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
