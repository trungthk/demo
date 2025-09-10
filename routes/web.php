<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/test', [HomeController::class, 'test']);
Route::get('/user', [UserController::class, 'index']);
Route::get('user/{user}', [UserController::class, 'show']);