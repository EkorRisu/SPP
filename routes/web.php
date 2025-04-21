<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Root
Route::get('/', function () {
    return view('welcome');
});

// Dashboard: Redirect berdasarkan role
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user(); // Gunakan Auth::user()

        if ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } elseif ($user->role === 'murid') {
            return redirect()->route('murid.dashboard');
        } else {
            abort(403, 'Unauthorized');
        }
    })->name('dashboard');
});

// Dashboard Guru (akses hanya untuk role: guru)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':guru'])->group(function () {
    Route::get('/guru/dashboard', function () {
        return view('guru.dashboard');
    })->name('guru.dashboard');
});

// Dashboard Murid (akses hanya untuk role: murid)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':murid'])->group(function () {
    Route::get('/murid/dashboard', function () {
        return view('murid.dashboard');
    })->name('murid.dashboard');
});

// Routes untuk Profil (untuk semua user yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Pembayaran untuk Murid (hanya user dengan role "murid")
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':murid'])->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
});

// Routes Pembayaran untuk Guru: Kelola Pembayaran (hanya user dengan role "guru")
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':guru'])->group(function () {
    Route::get('/payments/manage', [PaymentController::class, 'manage'])->name('payments.manage');
    Route::get('/payments/edit/{id}', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/update/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/delete/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
   
});

// Include Auth Routes (Login & Register)
require __DIR__.'/auth.php';
