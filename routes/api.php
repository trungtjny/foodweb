<?php

use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
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

Route::get('/category-with-product/{id}', [CategoryController::class, 'getProduct']);

Route::resource('shop', ShopController::class)->only(['index', 'show']);
Route::resource('category', CategoryController::class);
Route::resource('product', ProductController::class)->only(['index', 'show']);
Route::get('/products/sale', [ProductController::class, 'sale']);

Route::get('home-product', [ProductController::class, 'getHomeProducts']);
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login-social ', [AuthController::class, 'socialLogin']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::middleware(['auth:api', 'role:0',])->group(function () {
    Route::post('/change-password', [AuthController::class, 'update']);
    Route::get('/get-me', [AuthController::class, 'getInfo']);
    Route::resource('cart', CartController::class);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::resource('order', OrderController::class);
});

Route::prefix('admin')->middleware(['auth:api', 'role:1'])->group(function () {
    Route::resource('/product', ProductController::class);
    //Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::resource('/category', CategoryController::class);
    Route::resource('/shop', ShopController::class);
});
