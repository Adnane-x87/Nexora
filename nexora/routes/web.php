<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index');
});

Route::get('/shop', function () {
    return view('shop');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', ['activePage' => 'statistics']);
    })->name('dashboard');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
