<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgoraController;


Route::get('/', [AgoraController::class, 'index']);
Route::get('/agora-token', [AgoraController::class, 'getToken']);
