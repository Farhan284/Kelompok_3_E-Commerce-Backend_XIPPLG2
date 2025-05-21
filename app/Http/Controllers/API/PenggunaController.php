<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Pengguna",
 *     description="Pengguna"
 * )
 */
class Penggunacontroller extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/Pengguna",
     *     tags={"Pengguna"},
     *     summary="Menampilkan semua Pengguna",
     *     @OA\Response(
     *         response=200,
     *         description="List of Pengguna",
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
        $Pengguna = Pengguna::all();

        return response()->json([
            'success' => true,
            'message' => 'List of Pengguna',
            'data' => $Pengguna
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/Pengguna",
     *     tags={"Pengguna"},
     *     summary="Membuat Pengguna baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_Pengguna","email","password","alamat","nomor_telepon"},
     *             @OA\Property(property="nama_Pengguna", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="alamat", type="string"),
     *             @OA\Property(property="nomor_telepon", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pengguna created successfully"
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
            'nama_Pengguna' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:Pengguna,email',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:15',
            'role' => 'in:pembeli,penjual'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

      $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'role' => $request->role ?? 'pembeli',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna created',
            'data' => $pengguna
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/Pengguna/{id}",
     *     tags={"Pengguna"},
     *     summary="Menampilkan detail Pengguna berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna not found"
     *     )
     * )
     */
    public function show($id)
    {
        $Pengguna = Pengguna::find($id);

        if (!$Pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengguna found',
            'data' => $Pengguna
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/Pengguna/{id}",
     *     tags={"Pengguna"},
     *     summary="Mengupdate data Pengguna",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_Pengguna", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="alamat", type="string"),
     *             @OA\Property(property="nomor_telepon", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna updated successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $Pengguna = Pengguna::find($id);

        if (!$Pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_Pengguna' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:Pengguna,email,' . $id . ',id',
            'password' => 'sometimes|required|string|min:6',
            'alamat' => 'sometimes|required|string',
            'nomor_telepon' => 'sometimes|required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
         $pengguna->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pengguna updated successfully',
            'data' => $Pengguna
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/Pengguna/{id}",
     *     tags={"Pengguna"},
     *     summary="Menghapus Pengguna",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $Pengguna = Pengguna::find($id);

        if (!$Pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna not found'
            ], 404);
        }

        $Pengguna->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna deleted successfully'
        ], 200);
    }
}
