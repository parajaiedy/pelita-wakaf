<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WakafController;
use App\Http\Controllers\AuthController;

// Route Peta Utama (Bawaan)
Route::get('/', [WakafController::class, 'index'])->name('home');

// Route Peta Publik Full Screen (semua aset)
Route::get('/peta', [WakafController::class, 'petaPublik'])->name('peta.publik');

// Route Peta per Kategori: Wakaf & Aset Pemerintah — terpisah agar tidak tercampur
Route::get('/wakaf', [WakafController::class, 'petaWakaf'])->name('peta.wakaf');
Route::get('/aset-pemerintah', [WakafController::class, 'petaAsetPemerintah'])->name('peta.aset-pemerintah');

// Route Auth (Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Admin (Hanya bisa diakses jika SUDAH LOGIN)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [WakafController::class, 'admin'])->name('index');
    Route::get('/laporan-pdf', [WakafController::class, 'laporanPdf'])->name('laporanPdf');
    Route::get('/create', [WakafController::class, 'create'])->name('create');
    Route::post('/store', [WakafController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [WakafController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [WakafController::class, 'update'])->name('update');
    Route::patch('/update-status/{id}', [WakafController::class, 'updateTindakLanjut'])->name('updateStatus');
    Route::delete('/delete/{id}', [WakafController::class, 'destroy'])->name('destroy');

    // Backup data dan export
    Route::get('/backup', [WakafController::class, 'backup'])->name('backup');
    Route::get('/export-excel', [WakafController::class, 'exportExcel'])->name('exportExcel');
});