<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', [UserController::class, 'showLogin'])->name('register');
Route::post('/login', [UserController::class, 'login']);

// Register
Route::get('/register', [UserController::class, 'showRegister'])->name('login');
Route::post('/register', [UserController::class, 'register']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
