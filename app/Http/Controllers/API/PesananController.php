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
     * tags={"pesanan"},
     *     security={{"sanctum":{}}},
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
     * tags={"pesanan"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_user","tanggal_pesanan","status_pesanan","total_harga"},
     *             @OA\Property(property="id_user", type="integer"),
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
            'id_user' => 'required|exists:user,id',
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
     * tags={"pesanan"},
     *     security={{"sanctum":{}}},
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
        $pesanan = Pesanan::with('user')->find($id);

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
     * tags={"pesanan"},
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
     *             @OA\Property(property="id_user", type="integer"),
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
            'id_user' => 'sometimes|required|exists:user,id',
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
     * tags={"pesanan"},
     *     security={{"sanctum":{}}},
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

    /**
 * @OA\Post(
 *     path="/api/pesanan/checkout",
 *     tags={"Pesanan"},
 *     summary="Melakukan checkout dari keranjang dan membuat pesanan",
 * tags={"pesanan"},
     *     security={{"sanctum":{}}},
 *     description="Endpoint ini akan mengambil semua item dari keranjang pengguna, menghitung total harga, membuat pesanan baru, menyimpan detail pesanan, dan mengosongkan keranjang.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id_user"},
 *             @OA\Property(property="id_user", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Checkout berhasil",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Checkout berhasil"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="id_user", type="integer"),
 *                 @OA\Property(property="tanggal_pesanan", type="string", format="date-time"),
 *                 @OA\Property(property="status_pesanan", type="string"),
 *                 @OA\Property(property="total_harga", type="number", format="float")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Keranjang kosong",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Keranjang kosong")
 *         )
 *     )
 * )
 */

    public function checkout(Request $request)
{
    $userId = $request->input('id_user');

    // Ambil semua item cart milik user
    $cartItems = Cart::where('user_id', $userId)->get();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Keranjang kosong'
        ], 400);
    }

    $totalHarga = $cartItems->sum('total_price');

    // Buat pesanan baru
    $pesanan = Pesanan::create([
        'id_user' => $userId,
        'tanggal_pesanan' => now(),
        'status_pesanan' => 'pending',
        'total_harga' => $totalHarga,
    ]);

    // Simpan detail pesanan per produk
    foreach ($cartItems as $item) {
        Detailpesanan::create([
            'id_pesanan' => $pesanan->id, // ganti sesuai nama PK pesanan kamu
            'id_produk' => $item->id,
            'jumlah' => $item->quantity,
            'harga' => $item->total_price,
        ]);
    }

    // Kosongkan keranjang
    Cart::where('user_id', $userId)->delete();

    return response()->json([
        'success' => true,
        'message' => 'Checkout berhasil',
        'data' => $pesanan
    ]);
}

    
}
