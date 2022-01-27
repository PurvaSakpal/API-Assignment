<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTController;
use App\Http\Controllers\RegisterController;

Route::group(['middleware'=>'api'],function($router){
});
Route::post('/register',[RegisterController::class,'Register']);
Route::get('/users',[RegisterController::class,'Users']);
Route::post('/login',[RegisterController::class,'Login']);
Route::post("/logout",[RegisterController::class,'logout']);
