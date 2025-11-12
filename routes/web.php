<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\GaleryController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by theRouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User Authentication Routes
Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [UserAuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [UserAuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [UserAuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [UserAuthController::class, 'resetPassword'])->name('password.update');

// Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/galeri', [FotoController::class, 'index'])->name('galeri.index');
Route::get('/galeri/album/{judul}', [FotoController::class, 'showAlbum'])->name('galeri.album');
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda}', [AgendaController::class, 'show'])->name('agenda.show');
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
Route::get('/informasi/{informasi}', [InformasiController::class, 'show'])->name('informasi.show');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');

// API Routes for Like & Comment (AJAX)
Route::prefix('api')->group(function () {
    Route::post('/fotos/{fotoId}/like', [LikeController::class, 'toggle'])->name('api.like.toggle');
    Route::get('/fotos/{fotoId}/like/status', [LikeController::class, 'status'])->name('api.like.status');
    Route::get('/fotos/{fotoId}/comments', [CommentController::class, 'index'])->name('api.comments.index');
    Route::post('/fotos/{fotoId}/comments', [CommentController::class, 'store'])->name('api.comments.store');
    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy'])->name('api.comments.destroy');
});

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

// Admin Protected Routes
Route::middleware(['web.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::get('/profiles', [AdminController::class, 'profiles'])->name('profiles');
    Route::post('/profiles/update', [AdminController::class, 'profilesUpdate'])->name('profiles.update');
    
    // Agenda management routes
    Route::get('/agenda', [AdminController::class, 'agenda'])->name('agenda');
    Route::get('/agenda/create', [AdminController::class, 'agendaCreate'])->name('agenda.create');
    Route::post('/agenda', [AdminController::class, 'agendaStore'])->name('agenda.store');
    Route::get('/agenda/{agenda}/edit', [AdminController::class, 'agendaEdit'])->name('agenda.edit');
    Route::put('/agenda/{agenda}', [AdminController::class, 'agendaUpdate'])->name('agenda.update');
    Route::delete('/agenda/{agenda}', [AdminController::class, 'agendaDestroy'])->name('agenda.destroy');
    
    // Informasi management routes
    Route::get('/informasi', [AdminController::class, 'informasi'])->name('informasi');
    Route::get('/informasi/create', [AdminController::class, 'informasiCreate'])->name('informasi.create');
    Route::post('/informasi', [AdminController::class, 'informasiStore'])->name('informasi.store');
    Route::get('/informasi/{informasi}/edit', [AdminController::class, 'informasiEdit'])->name('informasi.edit');
    Route::put('/informasi/{informasi}', [AdminController::class, 'informasiUpdate'])->name('informasi.update');
    Route::delete('/informasi/{informasi}', [AdminController::class, 'informasiDestroy'])->name('informasi.destroy');
    
    // Hero Background management routes
    Route::get('/hero-background', [AdminController::class, 'heroBackground'])->name('hero-background');
    Route::post('/hero-background', [AdminController::class, 'heroBackgroundUpdate'])->name('hero-background.update');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Foto management routes - using AdminController for index and FotoController for CRUD
    Route::get('/fotos', [AdminController::class, 'fotos'])->name('fotos');
    Route::get('/tambah-foto', [FotoController::class, 'create'])->name('fotos.create');
    Route::post('/fotos', [FotoController::class, 'store'])->name('fotos.store');
    Route::get('/fotos/{foto}/edit', [FotoController::class, 'edit'])->name('fotos.edit');
    Route::post('/fotos/{foto}/update', [FotoController::class, 'update'])->name('fotos.update');
    Route::delete('/fotos/{foto}', [FotoController::class, 'destroy'])->name('fotos.destroy');
});

// Admin API routes - separate group to avoid double prefixing
Route::middleware(['web.auth'])->prefix('admin')->group(function () {
    Route::get('/api/galeries', [AdminController::class, 'getGaleries'])->name('admin.api.galeries');
    Route::get('/api/categories', [AdminController::class, 'getCategories'])->name('admin.api.categories');
    Route::get('/api/fotos/{foto}/album', [FotoController::class, 'getAlbumPhotos'])->name('admin.api.fotos.album');
    Route::post('/api/fotos/bulk-delete', [FotoController::class, 'bulkDelete'])->name('admin.api.fotos.bulk-delete');
    Route::post('/api/fotos/{foto}/add-photos', [FotoController::class, 'addPhotosToAlbum'])->name('admin.api.fotos.add-photos');
});
