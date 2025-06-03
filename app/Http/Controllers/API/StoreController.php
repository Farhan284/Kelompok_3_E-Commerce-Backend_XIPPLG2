<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Store",
 *     description="Manajemen Toko"
 * )
 */
class StoreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/store",
     *     summary="Ambil semua data toko",
     *     tags={"Store"},
     * tags={"store"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List semua toko",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Store"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Store::all());
    }

    /**
     * @OA\Post(
     *     path="/api/store",
     *     summary="Buat toko baru",
     * tags={"store"},
     *     security={{"sanctum":{}}},
     *     tags={"Store"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "city"},
     *             @OA\Property(property="name", type="string", example="Toko Baru"),
     *             @OA\Property(property="city", type="string", example="Jakarta"),
     *             @OA\Property(property="profile_image", type="string", example="http://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Toko berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'city' => 'required|string',
            'profile_image' => 'nullable|string'
        ]);

        $store = Store::create($data);
        return response()->json($store, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/store/{id}",
     *     summary="Ambil detail toko berdasarkan ID",
     * tags={"store"},
     *     security={{"sanctum":{}}},
     *     tags={"Store"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail toko",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     ),
     *     @OA\Response(response=404, description="Toko tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['message' => 'Toko tidak ditemukan'], 404);
        }
        return response()->json($store);
    }

    /**
     * @OA\Put(
     *     path="/api/store/{id}",
     *     summary="Update data toko",
     * tags={"store"},
     *     security={{"sanctum":{}}},
     *     tags={"Store"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Toko Update"),
     *             @OA\Property(property="city", type="string", example="Surabaya"),
     *             @OA\Property(property="profile_image", type="string", example="http://example.com/updated.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Toko berhasil diupdate",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     ),
     *     @OA\Response(response=404, description="Toko tidak ditemukan")
     * )
     */
    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['message' => 'Toko tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|string',
            'city' => 'sometimes|string',
            'profile_image' => 'nullable|string'
        ]);

        $store->update($data);
        return response()->json($store);
    }

    /**
     * @OA\Delete(
     *     path="/api/store/{id}",
     *     summary="Hapus toko berdasarkan ID",
     * tags={"store"},
     *     security={{"sanctum":{}}},
     *     tags={"Store"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Toko berhasil dihapus",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Toko berhasil dihapus"))
     *     ),
     *     @OA\Response(response=404, description="Toko tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['message' => 'Toko tidak ditemukan'], 404);
        }

        $store->delete();
        return response()->json(['message' => 'Toko berhasil dihapus']);
    }
}
