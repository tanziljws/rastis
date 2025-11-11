<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 * 
 * API endpoints untuk autentikasi user menggunakan Laravel Sanctum.
 */

class AuthController extends Controller
{
    /**
     * Login user dan dapatkan token
     * 
     * Endpoint ini digunakan untuk login user dan mendapatkan token akses.
     * Token yang didapat harus digunakan untuk mengakses endpoint yang memerlukan autentikasi.
     * 
     * @bodyParam name string Username atau nama user. Example: admin
     * @bodyParam email string Email user. Example: admin@example.com
     * @bodyParam password string required Password user. Example: password
     * 
     * @response 200 scenario="Login berhasil" {
     *   "message": "Login successful",
     *   "token": "1|abc123def456...",
     *   "user": {
     *     "id": 1,
     *     "name": "admin",
     *     "email": "admin@example.com"
     *   }
     * }
     * 
     * @response 422 scenario="Kredensial salah" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "credentials": ["The provided credentials are incorrect."]
     *   }
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'required|string',
        ], [
            'name.nullable' => 'Name atau email harus diisi salah satu.',
            'email.nullable' => 'Name atau email harus diisi salah satu.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Check if either name or email is provided
        if (!$request->filled('name') && !$request->filled('email')) {
            throw ValidationException::withMessages([
                'credentials' => ['Name atau email harus diisi salah satu.'],
            ]);
        }

        // Build query to find user by name or email
        $query = User::query();
        if ($request->filled('name')) {
            $query->where('name', $request->name);
        } elseif ($request->filled('email')) {
            $query->where('email', $request->email);
        }

        $user = $query->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Logout user dan revoke token
     * 
     * Endpoint ini digunakan untuk logout user dan mencabut token akses.
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
     * Dapatkan informasi user yang sedang login
     * 
     * Endpoint ini digunakan untuk mendapatkan informasi user yang sedang login.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Berhasil" {
     *   "user": {
     *     "id": 1,
     *     "name": "admin",
     *     "email": "admin@example.com"
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
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]
        ]);
    }
}
