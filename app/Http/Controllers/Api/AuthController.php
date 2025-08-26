<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 * 
 * API endpoints untuk autentikasi petugas menggunakan Laravel Sanctum.
 */

class AuthController extends Controller
{
    /**
     * Login petugas dan dapatkan token
     * 
     * Endpoint ini digunakan untuk login petugas dan mendapatkan token akses.
     * Token yang didapat harus digunakan untuk mengakses endpoint yang memerlukan autentikasi.
     * 
     * @bodyParam username string required Username petugas. Example: admin
     * @bodyParam password string required Password petugas. Example: admin123
     * 
     * @response 200 scenario="Login berhasil" {
     *   "message": "Login successful",
     *   "token": "1|abc123def456...",
     *   "petugas": {
     *     "id": 1,
     *     "username": "admin"
     *   }
     * }
     * 
     * @response 422 scenario="Kredensial salah" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "username": ["The provided credentials are incorrect."]
     *   }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $petugas = Petugas::where('username', $request->username)->first();

        if (!$petugas || !Hash::check($request->password, $petugas->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke existing tokens
        $petugas->tokens()->delete();

        // Create new token
        $token = $petugas->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'petugas' => [
                'id' => $petugas->id,
                'username' => $petugas->username,
            ]
        ]);
    }

    /**
     * Logout petugas dan revoke token
     * 
     * Endpoint ini digunakan untuk logout petugas dan mencabut token akses.
     * Setelah logout, token tidak dapat digunakan lagi untuk mengakses endpoint yang memerlukan autentikasi.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Logout berhasil" {
     *   "message": "Logout successful"
     * }
     * 
     * @response 401 scenario="Token tidak valid" {
     *   "message": "Unauthenticated."
     * }
     */
    public function logout(Request $request)
    {
        // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Dapatkan informasi petugas yang sedang login
     * 
     * Endpoint ini digunakan untuk mendapatkan informasi petugas yang sedang login.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Berhasil" {
     *   "petugas": {
     *     "id": 1,
     *     "username": "admin"
     *   }
     * }
     * 
     * @response 401 scenario="Token tidak valid" {
     *   "message": "Unauthenticated."
     * }
     */
    public function me(Request $request)
    {
        return response()->json([
            'petugas' => [
                'id' => $request->user()->id,
                'username' => $request->user()->username,
            ]
        ]);
    }
}
