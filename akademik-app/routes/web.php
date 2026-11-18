<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', DashboardController::class)->name('admin');
    Route::get('/manajemen-pengguna', DashboardController::class)->name('manajemen-pengguna');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen', DashboardController::class)->name('dosen');
});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa', DashboardController::class)->name('mahasiswa');
    Route::get('/nilai-pribadi', DashboardController::class)->name('nilai-pribadi');
});

Route::middleware(['auth', 'role:admin,dosen'])->group(function () {
    Route::get('/kelola-materi', DashboardController::class)->name('kelola-materi');
    Route::get('/kelola-nilai', DashboardController::class)->name('kelola-nilai');
});

Route::middleware(['auth', 'role:admin,dosen,mahasiswa'])->group(function () {
    Route::get('/lihat-materi', DashboardController::class)->name('lihat-materi');
});
