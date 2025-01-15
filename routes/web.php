<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
    });

    Route::prefix('/stores')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('stores.index');
        Route::get('/create', [StoreController::class, 'create'])->name('stores.create');
        Route::post('/store', [StoreController::class, 'store'])->name('stores.store');
        Route::get('/show/{id}', [StoreController::class, 'show'])->name('stores.show');
        Route::patch('/update/{id}', [StoreController::class, 'update'])->name('stores.update');
        Route::delete('/delete/{id}', [StoreController::class, 'destroy'])->name('stores.delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
