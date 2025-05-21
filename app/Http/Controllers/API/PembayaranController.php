<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Pembayaran",
 *     description="Pembayaran"
 * )
 */
class pembayarancontroller extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pembayaran",
     *     tags={"Pembayaran"},
     *     summary="Menampilkan semua pembayaran",
     *     @OA\Response(
     *         response=200,
     *         description="List of Pembayaran",
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
        $pembayaran = Pembayaran::with(['pesanan', 'pelanggan'])->get();

        return response()->json([
            'success' => true,
            'message' => 'List of Pembayaran',
            'data' => $pembayaran
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/pembayaran",
     *     tags={"Pembayaran"},
     *     summary="Membuat pembayaran baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_pesanan","id_pelanggan","metode_pembayaran","status_pembayaran","tanggal_pembayaran","total_pembayaran"},
     *             @OA\Property(property="id_pesanan", type="integer"),
     *             @OA\Property(property="id_pelanggan", type="integer"),
     *             @OA\Property(property="metode_pembayaran", type="string"),
     *             @OA\Property(property="status_pembayaran", type="string"),
     *             @OA\Property(property="tanggal_pembayaran", type="string", format="date"),
     *             @OA\Property(property="total_pembayaran", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pembayaran created successfully"
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
            'message' => 'Pembayaran created successfullysss',
            'data' => $pembayaran
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/pembayaran/{id}",
     *     tags={"Pembayaran"},
     *     summary="Menampilkan detail pembayaran berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pembayaran found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pembayaran not found"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/pembayaran/{id}",
     *     tags={"Pembayaran"},
     *     summary="Mengupdate data pembayaran",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_pesanan", type="integer"),
     *             @OA\Property(property="id_pelanggan", type="integer"),
     *             @OA\Property(property="metode_pembayaran", type="string"),
     *             @OA\Property(property="status_pembayaran", type="string"),
     *             @OA\Property(property="tanggal_pembayaran", type="string", format="date"),
     *             @OA\Property(property="total_pembayaran", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pembayaran updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pembayaran not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/pembayaran/{id}",
     *     tags={"Pembayaran"},
     *     summary="Menghapus pembayaran",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pembayaran deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pembayaran not found"
     *     )
     * )
     */
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
