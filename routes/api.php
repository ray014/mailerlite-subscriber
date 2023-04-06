<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/subscriber', [SubscriberController::class, 'index']);
Route::get('/subscriber/{id}', [SubscriberController::class, 'show']);
Route::post('/subscriber', [SubscriberController::class, 'store']);
Route::put('/subscriber/{id}', [SubscriberController::class, 'update']);
Route::delete('/subscriber/{id}', [SubscriberController::class, 'destroy']);

Route::post('/api-key', [SubscriberController::class, 'storeApiKey']);