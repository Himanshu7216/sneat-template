<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class,'loginPage'])->name('login');
Route::get('/register',[LoginController::class,'registerPage'])->name('register');
Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);
Route::get('/logout',[LoginController::class,'logout'])->name('logout');
// Route::get('/forgot_password',[LoginController::class,'forgot_password'])->name('forgot-password');



Route::get('/forgot-password', [LoginController::class, 'forgot_password'])->name('forgot-password-page');

Route::post('/forgot-password', [LoginController::class, 'send_reset_link'])
    ->name('forgot-password');

Route::get('/reset-password/{token}', [LoginController::class, 'reset_password'])
    ->name('reset-password');

Route::post('/new-password', [LoginController::class, 'new_password'])->name('new-password');

Route::middleware('auth')->group(function(){
    Route::get('/dashboard',[AdminController::class,'dashboard'])->name('dashboard');
    Route::get('/analytics',[AdminController::class,'analytics'])->name('analytics');
    Route::get('/profile',[ProfileController::class,'profile'])->name('show-profile');

    Route::put('/profile_update',[ProfileController::class,'profile_Update'])->name('profile.updateprofile.update');
    // Route::view('/dashboard', 'dashboard.index');
// Route::view('/profile', 'profile.index');
});
