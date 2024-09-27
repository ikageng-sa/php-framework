<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Qanna\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/login-user', [LoginController::class, 'login'])->name('auth.login');
Route::post('/register-user', [RegisterController::class, 'register'])->name('auth.register');

Route::post('/logout', function () {
	auth()->logout();
	return redirect()->to('\\');
})->name('logout');

Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');
