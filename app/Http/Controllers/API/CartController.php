<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="Manajemen data keranjang belanja"
 * )
 */
class CartController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Menampilkan semua data keranjang",
     * tags={"Cart"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menampilkan daftar keranjang",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Cart"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Cart::with(['user', 'produk'])->get());
    }

    /**
     * @OA\Post(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Menambahkan item ke keranjang",
     * tags={"Cart"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity", "total_price", "produk_id", "user_id"},
     *             @OA\Property(property="quantity", type="integer", example=2),
     *             @OA\Property(property="total_price", type="number", format="float", example=150000),
     *             @OA\Property(property="produk_id", type="integer", example=3),
     *             @OA\Property(property="user_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item berhasil ditambahkan ke keranjang",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'produk_id' => 'required|exists:produk,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $cart = Cart::create($data);
        return response()->json($cart, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/cart/{id}",
     *     tags={"Cart"},
     *     summary="Menampilkan satu data keranjang",
     * tags={"Cart"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID keranjang",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data keranjang ditemukan",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     ),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $cart = Cart::with(['user', 'produk'])->find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json($cart);
    }

    /**
     * @OA\Put(
     *     path="/api/cart/{id}",
     *     tags={"Cart"},
     *     summary="Memperbarui data keranjang",
     * tags={"Cart"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID keranjang",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", example=3),
     *             @OA\Property(property="total_price", type="number", format="float", example=225000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data keranjang berhasil diperbarui",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     ),
     *     @OA\Response(response=404, description="Cart tidak ditemukan")
     * )
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->update($request->only('quantity', 'total_price'));
        return response()->json($cart);
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/{id}",
     *     tags={"Cart"},
     *     summary="Menghapus item dari keranjang",
     * tags={"Cart"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID keranjang",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Berhasil dihapus"),
     *     @OA\Response(response=404, description="Cart tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->delete();
        return response()->json(null, 204);
    }
}
