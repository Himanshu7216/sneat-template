<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class,'loginPage'])->name('login');
Route::get('/register',[LoginController::class,'registerPage'])->name('register');
Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);
Route::get('/logout',[LoginController::class,'logout']);
// Route::get('/forgot_password',[LoginController::class,'forgot_password'])->name('forgot-password');
Route::get('/dashboard',function(){
    return view('dashboard');
});


Route::get('/forgot-password', [LoginController::class, 'forgot_password'])->name('forgot-password-page');

Route::post('/forgot-password', [LoginController::class, 'send_reset_link'])
    ->name('forgot-password');

Route::get('/reset-password/{token}', [LoginController::class, 'reset_password'])
    ->name('reset-password');

Route::post('/new-password', [LoginController::class, 'new_password'])->name('new-password');
