<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WakafController;
use App\Http\Controllers\AuthController;
use App\Models\AsetWakaf; // Menambahkan panggian Model agar aman

// Route Peta Utama (Bawaan)
Route::get('/', [WakafController::class, 'index'])->name('home');

// Route Peta Publik Full Screen (Baru)
Route::get('/peta', function () {
    // Memanggil data langsung dari Model, dijamin tidak akan salah nama tabel
    $asets = AsetWakaf::all(); 
    return view('peta_publik', compact('asets'));
});

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