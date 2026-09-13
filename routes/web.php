<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WakafController;
use App\Http\Controllers\AuthController;

// Route Peta Utama (Publik)
Route::get('/', [WakafController::class, 'index'])->name('home');

// Route Auth (Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Admin (Hanya bisa diakses jika SUDAH LOGIN)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [WakafController::class, 'admin'])->name('index');
    Route::get('/create', [WakafController::class, 'create'])->name('create');
    Route::post('/store', [WakafController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [WakafController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [WakafController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [WakafController::class, 'destroy'])->name('destroy');
    
    // Route Export Excel
    Route::get('/export-excel', [WakafController::class, 'exportExcel'])->name('exportExcel');
});