<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;

Route::get('/', function () {
    return redirect()->route('login.form');
});

// 🔐 Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [UserController::class, 'login'])->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::post('/register', [UserController::class, 'register'])->name('register');

// 🏠 Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    // Transaction CRUD
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Goals CRUD
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::put('/goals/{id}', [GoalController::class, 'update'])->name('goals.update');
    Route::delete('/goals/{id}', [GoalController::class, 'destroy'])->name('goals.destroy');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
