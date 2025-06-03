<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/review",
     *     summary="Menampilkan semua review",
     * tags={"review"},
     *     security={{"sanctum":{}}},
     *     tags={"Review"},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan daftar review"
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Review::with(['user', 'produk'])->get()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/review",
     *     summary="Membuat review baru",
     * tags={"review"},
     *     security={{"sanctum":{}}},
     *     tags={"Review"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "produk_id", "rating"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="produk_id", type="integer", example=3),
     *             @OA\Property(property="rating", type="integer", example=5),
     *             @OA\Property(property="review", type="string", example="Sangat puas!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berhasil membuat review"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        $review = Review::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dibuat',
            'data' => $review
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/review/{id}",
     *     summary="Menampilkan detail review",
     * tags={"review"},
     *     security={{"sanctum":{}}},
     *     tags={"Review"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan detail review"
     *     )
     * )
     */
    public function show($id)
    {
        $review = Review::with(['user', 'produk'])->find($id);

        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $review]);
    }

    /**
     * @OA\Put(
     *     path="/api/review/{id}",
     *     summary="Memperbarui review",
     * tags={"review"},
     *     security={{"sanctum":{}}},
     *     tags={"Review"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="integer", example=4),
     *             @OA\Property(property="review", type="string", example="Cukup bagus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil memperbarui review"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review tidak ditemukan'], 404);
        }

        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        $review->update($request->only(['rating', 'review']));

        return response()->json(['success' => true, 'message' => 'Review berhasil diperbarui', 'data' => $review]);
    }

    /**
     * @OA\Delete(
     *     path="/api/review/{id}",
     *     summary="Menghapus review",
     * tags={"review"},
     *     security={{"sanctum":{}}},
     *     tags={"Review"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil menghapus review"
     *     )
     * )
     */
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review tidak ditemukan'], 404);
        }

        $review->delete();

        return response()->json(['success' => true, 'message' => 'Review berhasil dihapus']);
    }
}
