<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $akun = Akun::where('email', $request->email)->first();

        if (!$akun || !Hash::check($request->password, $akun->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Buat token baru dengan Laravel Sanctum
        $token = $akun->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'id' => $akun->id,
                'nama' => $akun->nama,
                'email' => $akun->email,
                'role' => $akun->role,
                'foto' => $akun->foto,
                'created_at' => $akun->created_at,
                'updated_at' => $akun->updated_at,
            ],
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:akuns,email',
            'password' => 'required',
            'role' => 'required|in:dokter,pemilik',
        ]);

        $akun = Akun::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => $akun,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
