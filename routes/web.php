<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class,'loginPage'])->name('login');
Route::get('/register',[LoginController::class,'registerPage'])->name('register');
Route::post('/register',[LoginController::class,'register']);
Route::post('/login',[LoginController::class,'login']);

Route::get('/dashboard',function(){
    return view('dashboard');
});

Route::get('/logout',[LoginController::class,'logout']);
