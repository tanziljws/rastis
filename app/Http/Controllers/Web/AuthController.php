<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        // Revoke existing tokens (khusus web-token)
        $user->tokens()->where('name', 'web-token')->delete();

        // Create new token
        $token = $user->createToken('web-token')->plainTextToken;

        // Store token and minimal user info in session for web access
        session([
            'admin_token' => $token,
            'admin_user_id' => $user->id,
            'admin_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        // Revoke the current token
        if (session('admin_token')) {
            $id = session('admin_user_id');
            if ($id) {
                $user = User::find($id);
                if ($user) {
                    $user->tokens()->where('name', 'web-token')->delete();
                }
            }
        }

        // Clear session
        session()->forget(['admin_token', 'admin_user', 'admin_user_id']);

        return redirect()->route('admin.login');
    }
}
