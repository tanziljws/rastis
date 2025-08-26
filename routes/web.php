<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;

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

// Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

// Admin Protected Routes
Route::middleware(['web.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::get('/galeries', [AdminController::class, 'galeries'])->name('galeries');
    Route::get('/fotos', [AdminController::class, 'fotos'])->name('fotos');
    Route::get('/profiles', [AdminController::class, 'profiles'])->name('profiles');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
