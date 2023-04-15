<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store']);
Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show']);
Route::put('/products/{id}', [\App\Http\Controllers\ProductController::class, 'update']);
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index']);
Route::delete('/products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy']);

Route::post('/cart', [\App\Http\Controllers\CartController::class, 'store']);
Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout']);

Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index']);

Route::get('/summary', [\App\Http\Controllers\SummaryController::class, 'index']);

