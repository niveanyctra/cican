<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Frontend\HomeController;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AccountController::class, 'register'])->name('register');
Route::post('buat-akun', [AccountController::class, 'registerProcess'])->name('register.process');

Route::get('/{username}', [AccountController::class, 'show'])->name('show');

Route::controller(AccountController::class)->prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::post('/{id}/update', 'update')->name('update');
    Route::get('/{id}/destroy', 'destroy')->name('destroy');
});
