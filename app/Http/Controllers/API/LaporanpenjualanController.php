<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Laporan Penjualan",
 *     description="Laporan Penjualan"
 * )
 */
class laporanpenjualancontroller extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/laporan-penjualan",
     *     tags={"Laporan Penjualan"},
     *     summary="Menampilkan semua laporan penjualan",
     *     @OA\Response(
     *         response=200,
     *         description="List of Laporan Penjualan",
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
        $laporan = LaporanPenjualan::with(['pesanan', 'pembayaran'])->get();

        return response()->json([
            'success' => true,
            'message' => 'List of Laporan Penjualan',
            'data' => $laporan
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/laporan-penjualan",
     *     tags={"Laporan Penjualan"},
     *     summary="Membuat laporan penjualan baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_pesanan","id_pembayaran","periode","total_penjualan","total_pendapatan","tanggal_laporan"},
     *             @OA\Property(property="id_pesanan", type="integer"),
     *             @OA\Property(property="id_pembayaran", type="integer"),
     *             @OA\Property(property="periode", type="string"),
     *             @OA\Property(property="total_penjualan", type="number", format="float"),
     *             @OA\Property(property="total_pendapatan", type="number", format="float"),
     *             @OA\Property(property="tanggal_laporan", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Laporan Penjualan created successfully"
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
            'id_pembayaran' => 'required|exists:pembayaran,id',
            'periode' => 'required|string|max:255',
            'total_penjualan' => 'required|numeric|min:0',
            'total_pendapatan' => 'required|numeric|min:0',
            'tanggal_laporan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $laporan = LaporanPenjualan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan created successfully',
            'data' => $laporan
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/laporan-penjualan/{id}",
     *     tags={"Laporan Penjualan"},
     *     summary="Menampilkan detail laporan penjualan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Laporan Penjualan found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Laporan Penjualan not found"
     *     )
     * )
     */
    public function show($id)
    {
        $laporan = LaporanPenjualan::with(['pesanan', 'pembayaran'])->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan Penjualan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan found',
            'data' => $laporan
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/laporan-penjualan/{id}",
     *     tags={"Laporan Penjualan"},
     *     summary="Mengupdate laporan penjualan",
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
     *             @OA\Property(property="id_pembayaran", type="integer"),
     *             @OA\Property(property="periode", type="string"),
     *             @OA\Property(property="total_penjualan", type="number", format="float"),
     *             @OA\Property(property="total_pendapatan", type="number", format="float"),
     *             @OA\Property(property="tanggal_laporan", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Laporan Penjualan updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Laporan Penjualan not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan Penjualan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_pesanan' => 'sometimes|required|exists:pesanan,id',
            'id_pembayaran' => 'sometimes|required|exists:pembayaran,id',
            'periode' => 'sometimes|required|string|max:255',
            'total_penjualan' => 'sometimes|required|numeric|min:0',
            'total_pendapatan' => 'sometimes|required|numeric|min:0',
            'tanggal_laporan' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $laporan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan updated successfully',
            'data' => $laporan
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/laporan-penjualan/{id}",
     *     tags={"Laporan Penjualan"},
     *     summary="Menghapus laporan penjualan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Laporan Penjualan deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Laporan Penjualan not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan Penjualan not found'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan Penjualan deleted successfully'
        ], 200);
    }
}
