<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Validator;

class detailpesananController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/detailpesanan",
 *     summary="Get list of detail pesanan",
 *     tags={"Detail Pesanan"},
 *     operationId="getDetailPesanan",
 *     description="Retrieve a list of all detail pesanan",
 *     @OA\Response(
 *         response=200,
 *         description="List of detail pesanan retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="List of Detail Pesanan"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="id_pesanan", type="integer", example=10),
 *                     @OA\Property(property="id_produk", type="integer", example=5),
 *                     @OA\Property(property="jumlah", type="integer", example=2),
 *                     @OA\Property(property="subtotal", type="number", format="float", example=15000.00),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-23T10:00:00.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-23T10:00:00.000000Z")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
public function index()
    {
        $details = DetailPesanan::all();
        
        return response()->json([
            'success' => true,
            'message' => 'List of Detail Pesanan',
            'data' => $details
        ], 200);
    }

 /**
 * @OA\Post(
 *     path="/api/detailpesanan",
 *     summary="Create a new Detail Pesanan",
 *     tags={"Detail Pesanan"},
 *     operationId="createDetailPesanan",
 *     description="Store a new detail pesanan in the database",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id_pesanan", "id_produk", "jumlah", "subtotal"},
 *             @OA\Property(property="id_pesanan", type="integer", example=1),
 *             @OA\Property(property="id_produk", type="integer", example=3),
 *             @OA\Property(property="jumlah", type="integer", example=2),
 *             @OA\Property(property="subtotal", type="number", format="float", example=30000.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Detail Pesanan created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Detail Pesanan created successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="id_pesanan", type="integer", example=1),
 *                 @OA\Property(property="id_produk", type="integer", example=3),
 *                 @OA\Property(property="jumlah", type="integer", example=2),
 *                 @OA\Property(property="subtotal", type="number", format="float", example=30000.00)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validation errors")
 * )
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_pesanan' => 'required|exists:pesanan,id',
        'id_produk' => 'required|exists:produk,id',
        'jumlah' => 'required|integer|min:1',
        'subtotal' => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422);
    }

    $detailPesanan = DetailPesanan::create($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Detail Pesanan created successfully',
        'data' => $detailPesanan
    ], 201);
}

 /**
 * @OA\Get(
 *     path="/api/detailpesanan/{id}",
 *     summary="Get Detail Pesanan by ID",
 *     tags={"Detail Pesanan"},
 *     operationId="getDetailPesananById",
 *     description="Retrieve a single detail pesanan by its ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detail Pesanan found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Detail Pesanan found"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="id_pesanan", type="integer", example=1),
 *                 @OA\Property(property="id_produk", type="integer", example=3),
 *                 @OA\Property(property="jumlah", type="integer", example=2),
 *                 @OA\Property(property="subtotal", type="number", format="float", example=30000.00)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Detail Pesanan not found")
 * )
 */
public function show($id)
{
    $detailPesanan = DetailPesanan::find($id);

    if (!$detailPesanan) {
        return response()->json([
            'success' => false,
            'message' => 'Detail Pesanan not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Detail Pesanan found',
        'data' => $detailPesanan
    ], 200);
}

 /**
 * @OA\Put(
 *     path="/api/detailpesanan/{id}",
 *     summary="Update existing Detail Pesanan",
 *     tags={"Detail Pesanan"},
 *     operationId="updateDetailPesanan",
 *     description="Update jumlah atau subtotal dari detail pesanan berdasarkan ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="jumlah", type="integer", example=4),
 *             @OA\Property(property="subtotal", type="number", format="float", example=60000.00)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detail Pesanan updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Detail Pesanan updated successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="id_pesanan", type="integer", example=1),
 *                 @OA\Property(property="id_produk", type="integer", example=3),
 *                 @OA\Property(property="jumlah", type="integer", example=4),
 *                 @OA\Property(property="subtotal", type="number", format="float", example=60000.00)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Detail Pesanan not found"),
 *     @OA\Response(response=422, description="Validation errors")
 * )
 */
public function update(Request $request, $id)
    {
        $detailPesanan = DetailPesanan::find($id);

        if (!$detailPesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Pesanan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jumlah' => 'sometimes|required|integer|min:1',
            'subtotal' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $detailPesanan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan updated successfully',
            'data' => $detailPesanan
        ], 200);
    }
    
/**
 * @OA\Delete(
 *     path="/api/detailpesanan/{id}",
 *     summary="Delete Detail Pesanan by ID",
 *     tags={"Detail Pesanan"},
 *     operationId="deleteDetailPesanan",
 *     description="Hapus data detail pesanan berdasarkan ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detail Pesanan deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Detail Pesanan deleted successfully")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Detail Pesanan not found")
 * )
 */


    

   

  

    

    public function destroy($id)
    {
        $detailPesanan = DetailPesanan::find($id);

        if (!$detailPesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Pesanan not found'
            ], 404);
        }

        $detailPesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detail Pesanan deleted successfullyssss'
        ], 200);
    }
}

