<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\TechController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bot', [BotController::class, 'index']);
Route::post('/bot', [BotController::class, 'input']);


//Tech part of site
Route::get('/tech/php', [TechController::class, 'phpinfo']);
Route::get('/tech/put_session', [TechController::class, 'putSession']);
Route::get('/tech/get_session', [TechController::class, 'getSession']);
Route::get('/tech/encrypt', [TechController::class, 'encrypt']);
Route::get('/tech/decrypt', [TechController::class, 'decrypt']);
Route::get('/tech/json', [TechController::class, 'json_test']);
