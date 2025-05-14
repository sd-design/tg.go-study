<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TechController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bot', [TelegramController::class, 'handleWebhook']);
Route::post('/bot',[TelegramController::class, 'handleWebhook']);


//Tech part of site
Route::get('/tech/php', [TechController::class, 'phpinfo']);
Route::get('/tech/put_session', [TechController::class, 'putSession']);
Route::get('/tech/get_session', [TechController::class, 'getSession']);
Route::get('/tech/encrypt', [TechController::class, 'encrypt']);
Route::get('/tech/decrypt', [TechController::class, 'decrypt']);
Route::get('/tech/json', [TechController::class, 'json_test']);
Route::get('/tech/db', [TechController::class, 'db_test']);
Route::get('/tech/bot', [TechController::class, 'insert_action']);

// Clear cache
Route::get('/tech/clear_cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Кэш очищен.";});
