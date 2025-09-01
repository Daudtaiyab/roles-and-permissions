<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('permissions/list', [PermissionController::class, 'index'])->name('permissions.list');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('permissions/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::post('permissions/delete/', [PermissionController::class, 'destroy'])->name('permissions.delete');

    Route::get('roles/list', [RoleController::class, 'index'])->name('roles.list');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::post('roles/delete/', [RoleController::class, 'destroy'])->name('roles.delete');

    Route::get('articles/list', [ArticleController::class, 'index'])->name('articles.list');
    Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('articles/store', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::post('articles/update/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::post('articles/delete/', [ArticleController::class, 'destroy'])->name('articles.delete');

    Route::get('users/list', [UsersController::class, 'index'])->name('users.list');
    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('users/store', [UsersController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::post('users/update/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('users/delete/', [UsersController::class, 'destroy'])->name('users.delete');
});

require __DIR__ . '/auth.php';