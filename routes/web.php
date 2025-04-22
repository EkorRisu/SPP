<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

// Root
Route::get('/', function () {
    return view('welcome');
});

// Dashboard: Redirect berdasarkan role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
});

// Dashboard Guru (akses hanya untuk role: guru)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guru'])->name('guru.dashboard');
});

// Dashboard Murid (akses hanya untuk role: murid)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':murid'])->prefix('murid')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'murid'])->name('murid.dashboard');
});

// Routes untuk Profil (untuk semua user yang login)
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Pembayaran untuk Murid (hanya user dengan role "murid")
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':murid'])->prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/receipt/{id}', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
});

// Routes Pembayaran untuk Guru: Kelola Pembayaran (hanya user dengan role "guru")
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':guru'])->prefix('payments')->group(function () {
    Route::get('/manage', [PaymentController::class, 'manage'])->name('payments.manage');
    Route::get('/edit/{id}', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::post('/update/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/delete/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::patch('/verify/{id}', [PaymentController::class, 'verify'])->name('payments.verify');
    
});

// Routes untuk membuat pembayaran (untuk semua user yang login)
Route::middleware(['auth'])->prefix('payments')->group(function () {
    Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
});

// Include Auth Routes (Login & Register)
require __DIR__.'/auth.php';
