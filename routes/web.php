<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MobilController as AdminMobilController;
use App\Http\Controllers\Admin\KriteriaController as AdminKriteriaController;

// Guest Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/mobil', [MobilController::class, 'index'])->name('mobil.index');
Route::get('/mobil/{mobil}', [MobilController::class, 'show'])->name('mobil.show');
Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');
Route::post('/perhitungan', [PerhitunganController::class, 'calculate'])->name('perhitungan.calculate');

// Kriteria Routes (CRUD - dapat diakses dengan middleware auth jika diperlukan)
Route::prefix('kriteria')->name('kriteria.')->group(function () {
    Route::get('/', [KriteriaController::class, 'index'])->name('index');
    Route::get('/create', [KriteriaController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/', [KriteriaController::class, 'store'])->name('store')->middleware('auth');
    Route::get('/{kriteria}/edit', [KriteriaController::class, 'edit'])->name('edit')->middleware('auth');
    Route::put('/{kriteria}', [KriteriaController::class, 'update'])->name('update')->middleware('auth');
    Route::delete('/{kriteria}', [KriteriaController::class, 'destroy'])->name('destroy')->middleware('auth');
    Route::patch('/{kriteria}/toggle-active', [KriteriaController::class, 'toggleActive'])->name('toggleActive')->middleware('auth');
});

// Auth Routes
Route::get('/login', function () { return view('auth.login'); })->name('login')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Mobil Admin Routes
    Route::prefix('mobil')->name('mobil.')->group(function () {
        Route::get('/', [AdminMobilController::class, 'index'])->name('index');
        Route::get('/create', [AdminMobilController::class, 'create'])->name('create');
        Route::post('/', [AdminMobilController::class, 'store'])->name('store');
        Route::get('/{mobil}/edit', [AdminMobilController::class, 'edit'])->name('edit');
        Route::put('/{mobil}', [AdminMobilController::class, 'update'])->name('update');
        Route::delete('/{mobil}', [AdminMobilController::class, 'destroy'])->name('destroy');
    });

    // Kriteria Admin Routes
    Route::prefix('kriteria')->name('kriteria.')->group(function () {
        Route::get('/', [AdminKriteriaController::class, 'index'])->name('index');
        Route::get('/{kriteria}/edit', [AdminKriteriaController::class, 'edit'])->name('edit');
        Route::put('/{kriteria}', [AdminKriteriaController::class, 'update'])->name('update');
    });
});

