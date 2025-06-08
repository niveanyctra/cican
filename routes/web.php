<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\FollowController;
use App\Http\Controllers\Backend\SearchController;
use App\Http\Controllers\Backend\AccountController;
use App\Http\Controllers\Backend\Post\LikeController;
use App\Http\Controllers\Backend\Post\PostController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\Post\CommentController;

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('register', [AccountController::class, 'create'])->name('register');
Route::post('buat-akun', [AccountController::class, 'store'])->name('register.process');

// Profil Pengguna
Route::get('/{username}', [AccountController::class, 'show'])->name('user.show');

Route::middleware('auth')->group(function () {
    // Route yang memerlukan autentikasi
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/mention/users', [CommentController::class, 'mention'])->name('mention.users');

    Route::controller(AccountController::class)->prefix('account')->name('account.')->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::get('/destroy', 'destroy')->name('destroy');
    });

    Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
        Route::get('/{id}/show', 'show')->name('show');
        Route::get('/{id}/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::post('/{id}/update', 'update')->name('update');
        Route::get('/{id}/destroy', 'destroy')->name('destroy');
    });

    Route::post('/posts/{id}/like', [LikeController::class, 'like'])->name('like');
    Route::delete('/posts/{id}/like', [LikeController::class, 'like']);
    // Route::controller(LikeController::class)->prefix('posts')->name('likes.')->group(function () {
    //     Route::post('/{post}/like', 'store')->name('store');
    //     Route::delete('/{post}/like', 'destroy')->name('destroy');
    // });

    Route::controller(CommentController::class)->prefix('posts')->name('comments.')->group(function () {
        Route::post('/{post}/comments', 'store')->name('store');
        Route::get('/comments/{comment}', 'destroy')->name('destroy');
    });

    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/mark-as-read', 'markAsRead')->name('markAsRead');
    });

    Route::controller(SearchController::class)->prefix('search')->name('search.')->group(function () {
        Route::get('/user', 'searchUser')->name('user');
    });

    Route::controller(FollowController::class)->prefix('follow')->name('follow.')->group(function () {
        Route::post('/follow-toggle/{user}', 'toggle')->name('toggle');
        Route::post('/follow/{user}', 'follow')->name('follow');
        Route::post('/unfollow/{user}', 'unfollow')->name('unfollow');
    });

});
