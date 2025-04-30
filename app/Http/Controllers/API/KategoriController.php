<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;

class kategoricontroller extends Controller
{
    
        /**
 * @OA\Get(
 *     path="/api/category",
 *     summary="Get list of kategories",
 *     tags={"kategory"},
 *     operationId="getCategories",
 *     description="Retrieve a list of book categories",
 *     @OA\Response(
 *         response=200,
 *         description="List of categories retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="List of Kategori"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="nama_kategori", type="string", example="Fiction")
 *                 )
 *             )
 *         )
 *     )
 * )
 */

    public function index()
    {
        $kategori = Kategori::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Kategori',
            'data' => $kategori
        ], 200);
    }
/**
 * @OA\Post(
 *     path="/api/category",
 *     summary="Create a new Kategori",
 *     tags={"kategory"},
 *     operationId="createCategory",
 *     description="Store a new kategori into the database",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nama_kategori"},
 *             @OA\Property(property="nama_kategori", type="string", example="Fiction")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Kategori created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Kategori created successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="nama_kategori", type="string", example="Fiction")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validation error")
 * )
 */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori = Kategori::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori created successfully',
            'data' => $kategori
        ], 201);
    }
/**
 * @OA\Get(
 *     path="/api/category/{id}",
 *     summary="Get a Kategori by ID",
 *     tags={"kategory"},
 *     operationId="getCategoryById",
 *     description="Retrieve a single kategori by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Kategori found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Kategori found"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="nama_kategori", type="string", example="Fiction")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Kategori not found")
 * )
 */

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kategori found',
            'data' => $kategori
        ], 200);
    }
/**
 * @OA\Put(
 *     path="/api/category/{id}",
 *     summary="Update a Kategori",
 *     tags={"kategory"},
 *     operationId="updateCategory",
 *     description="Update an existing kategori's name",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="nama_kategori", type="string", example="Novel")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Kategori updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Kategori updated successfully"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="nama_kategori", type="string", example="Novel")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Kategori not found"),
 *     @OA\Response(response=422, description="Validation errors")
 * )
 */

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:255|unique:kategori,nama_kategori,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori updated successfully',
            'data' => $kategori
        ], 200);
    }

    /**
 * @OA\Delete(
 *     path="/api/category/{id}",
 *     summary="Delete a Kategori by ID",
 *     tags={"kategory"},
 *     operationId="deleteCategory",
 *     description="Remove a kategori from the system",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Kategori deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Kategori deleted successfully")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Kategori not found")
 * )
 */

    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori not found'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori deleted successfully'
        ], 200);
    }
}

