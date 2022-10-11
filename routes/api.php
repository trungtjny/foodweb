<?php

use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::resource('category', CategoryController::class)->only(['index', 'show']);
Route::resource('product', ProductController::class)->only(['index', 'show']);
Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::resource('shop', ShopController::class)->only(['index', 'show']);
Route::middleware(['auth:api','role:0'])->group(function(){
    Route::get('/get-me', [AuthController::class, 'getInfo']); 
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::resource('cart', CartController::class);
    Route::resource('order', OrderController::class);
    Route::resource('product', ProductController::class);

    Route::resource('shop', ShopController::class);
});