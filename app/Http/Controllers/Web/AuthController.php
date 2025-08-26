<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $petugas = Petugas::where('username', $request->username)->first();

        if (!$petugas || !Hash::check($request->password, $petugas->password)) {
            throw ValidationException::withMessages([
                'username' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Revoke existing tokens
        $petugas->tokens()->delete();

        // Create new token
        $token = $petugas->createToken('web-token')->plainTextToken;

        // Store token in session for web access
        session(['admin_token' => $token]);
        session(['admin_user' => $petugas]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        // Revoke the current token
        if (session('admin_token')) {
            $petugas = Petugas::find(session('admin_user.id'));
            if ($petugas) {
                $petugas->tokens()->where('name', 'web-token')->delete();
            }
        }

        // Clear session
        session()->forget(['admin_token', 'admin_user']);

        return redirect()->route('admin.login');
    }
}
