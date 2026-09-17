<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

// Rute untuk Login (Bisa diakses siapa saja tanpa login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute untuk Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// KUMPULAN RUTE AMAN (Hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('dashboard');
    Route::get('/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/report', [TransactionController::class, 'report'])->name('transactions.report');
    
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});