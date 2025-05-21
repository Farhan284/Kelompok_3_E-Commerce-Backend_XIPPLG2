<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email' => 'required|string|email|unique:pengguna',
            'password' => 'required|string|min:6',
            'role' => 'in:pembeli,penjual'
        ]);

        $pengguna = Pengguna::create([
            'nama_pengguna' => $request->nama_pengguna,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'pembeli'
        ]);

        $token = $pengguna->createToken('token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Register success',
            'token' => $token,
            'data' => $pengguna
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            return response()->json(['success' => false, 'message' => 'Login gagal'], 401);
        }

        $token = $pengguna->createToken('token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'data' => $pengguna
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out'
        ]);
    }
}
