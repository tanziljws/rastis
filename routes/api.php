<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\GaleryController;
use App\Http\Controllers\Api\FotoController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua route API ada di sini.
|
*/

// ========================
// Public routes
// ========================
Route::post('/login', [AuthController::class, 'login']);

// Guest routes (read-only)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::get('/galeries', [GaleryController::class, 'index']);
Route::get('/galeries/{galery}', [GaleryController::class, 'show']);
Route::get('/fotos', [FotoController::class, 'index']);
Route::get('/fotos/{foto}', [FotoController::class, 'show']);
Route::get('/profiles', [ProfileController::class, 'index']);
Route::get('/profiles/{profile}', [ProfileController::class, 'show']);

// ========================
// Protected routes (Admin only)
// ========================
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Admin dashboard routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/categories', [AdminController::class, 'categories']);
        Route::get('/posts', [AdminController::class, 'posts']);
    });

    // Full CRUD routes for admin
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('posts', PostController::class)->except(['index', 'show']);
    Route::apiResource('galeries', GaleryController::class)->except(['index', 'show']);
    Route::apiResource('fotos', FotoController::class)->except(['index', 'show']);
    Route::apiResource('profiles', ProfileController::class)->except(['index', 'show']);
});

// Default user route
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
