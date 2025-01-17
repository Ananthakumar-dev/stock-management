<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
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
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

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

    Route::prefix('/measurements')->group(function () {
        Route::get('/', [MeasurementController::class, 'index'])->name('measurements.index');
        Route::get('/create', [MeasurementController::class, 'create'])->name('measurements.create');
        Route::post('/store', [MeasurementController::class, 'store'])->name('measurements.store');
        Route::get('/show/{id}', [MeasurementController::class, 'show'])->name('measurements.show');
        Route::patch('/update/{id}', [MeasurementController::class, 'update'])->name('measurements.update');
        Route::delete('/delete/{id}', [MeasurementController::class, 'destroy'])->name('measurements.delete');
    });

    Route::prefix('/attributes')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('attributes.index');
        Route::get('/create', [AttributeController::class, 'create'])->name('attributes.create');
        Route::post('/store', [AttributeController::class, 'store'])->name('attributes.store');
        Route::get('/show/{id}', [AttributeController::class, 'show'])->name('attributes.show');
        Route::patch('/update/{id}', [AttributeController::class, 'update'])->name('attributes.update');
        Route::delete('/delete/{id}', [AttributeController::class, 'destroy'])->name('attributes.delete');

        Route::get('/attributes', [AttributeController::class, 'get'])->name('attributes.get');
    });

    Route::prefix('/items')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('items.index');
        Route::get('/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/store', [ItemController::class, 'store'])->name('items.store');
        Route::get('/show/{id}', [ItemController::class, 'show'])->name('items.show');
        Route::post('/update/{id}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/delete/{id}', [ItemController::class, 'destroy'])->name('items.delete');
    });

    Route::prefix('/inventories')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventories.index');
        Route::get('/create', [InventoryController::class, 'create'])->name('inventories.create');
        Route::post('/store', [InventoryController::class, 'store'])->name('inventories.store');
        Route::get('/show/{id}', [InventoryController::class, 'show'])->name('inventories.show');
        Route::post('/update/{id}', [InventoryController::class, 'update'])->name('inventories.update');
        Route::delete('/delete/{id}', [InventoryController::class, 'destroy'])->name('inventories.delete');

        Route::get('/basicData', [InventoryController::class, 'basicData'])->name('inventories.basicData');
        Route::get('/itemDetails/{id}', [InventoryController::class, 'itemDetails'])->name('inventories.itemDetails');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
