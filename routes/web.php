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
Route::get('/export-pdf', [TransactionController::class, 'exportPdf'])->name('transactions.exportPdf');

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
    Route::delete('/goals/savings/{id}', [GoalSavingController::class, 'destroy'])->name('goals.savings.destroy');    
    Route::put('/goals/savings/{id}', [GoalSavingController::class, 'update'])->name('goals.savings.update');



    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
