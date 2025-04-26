<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Pesanan",
 *     description="Manajemen Pesanan"
 * )
 */
class PesananController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pesanan",
     *     tags={"Pesanan"},
     *     summary="Menampilkan semua pesanan",
     *     @OA\Response(
     *         response=200,
     *         description="List of Pesanan",
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
        $pesanan = Pesanan::with('pelanggan')->get();

        return response()->json([
            'success' => true,
            'message' => 'List of Pesanan',
            'data' => $pesanan
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/pesanan",
     *     tags={"Pesanan"},
     *     summary="Membuat pesanan baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_pelanggan","tanggal_pesanan","status_pesanan","total_harga"},
     *             @OA\Property(property="id_pelanggan", type="integer"),
     *             @OA\Property(property="tanggal_pesanan", type="string", format="date"),
     *             @OA\Property(property="status_pesanan", type="string"),
     *             @OA\Property(property="total_harga", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pesanan created successfully"
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

    /**
     * @OA\Get(
     *     path="/api/pesanan/{id}",
     *     tags={"Pesanan"},
     *     summary="Menampilkan detail pesanan berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pesanan found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pesanan not found"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/pesanan/{id}",
     *     tags={"Pesanan"},
     *     summary="Mengupdate data pesanan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_pelanggan", type="integer"),
     *             @OA\Property(property="tanggal_pesanan", type="string", format="date"),
     *             @OA\Property(property="status_pesanan", type="string"),
     *             @OA\Property(property="total_harga", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pesanan updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pesanan not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/pesanan/{id}",
     *     tags={"Pesanan"},
     *     summary="Menghapus pesanan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pesanan deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pesanan not found"
     *     )
     * )
     */
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
