<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalSavingController;

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

    Route::get('/password/edit', [UserController::class, 'editPassword'])->name('auth.editPassword');
    Route::post('/password/update', [UserController::class, 'updatePassword'])->name('auth.updatePassword');


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

    // 🏦 Goal Savings CRUD
    Route::prefix('goals/{goalId}/savings')->group(function () {
        Route::get('/', [GoalSavingController::class, 'index'])->name('goals.savings.index');   // list tabungan per goal
        Route::post('/', [GoalSavingController::class, 'store'])->name('goals.savings.store');  // tambah tabungan
    });

    Route::get('/savings/{id}/edit', [GoalSavingController::class, 'edit'])->name('goals.savings.edit');
    Route::put('/savings/{id}', [GoalSavingController::class, 'update'])->name('goals.savings.update');
    Route::delete('/savings/{id}', [GoalSavingController::class, 'destroy'])->name('goals.savings.destroy');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
