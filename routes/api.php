<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoucherController;
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
Route::get('/blog', [PostController::class, 'blog']);
Route::get('/sale', [PostController::class, 'sale']);
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login-social ', [AuthController::class, 'socialLogin']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::resource('admin/slider', SliderController::class);
Route::middleware(['auth:api', 'role:0',])->group(function () {
    Route::post('/change-password', [AuthController::class, 'update']);
    Route::get('/get-me', [AuthController::class, 'getInfo']);
    Route::resource('cart', CartController::class);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::resource('order', OrderController::class);
    Route::post('use-voucher', [OrderController::class, 'useVoucher']);
});

Route::prefix('admin')
    ->middleware(['auth:api', 'role:2'])
    ->group(function () {
        Route::resource('/product', ProductController::class);
        //Route::post('/product/{id}', [ProductController::class, 'update']);
        Route::resource('/category', CategoryController::class);
        Route::resource('/shop', ShopController::class);
        Route::resource('/post', PostController::class);
        Route::resource('/voucher', VoucherController::class);
        Route::get('/voucher-use', [VoucherController::class, 'listUse']);

        Route::post('/order', [AdminOrderController::class, 'getlist']);
        Route::post('/order/{id}', [AdminOrderController::class, 'updateStatus']);
        Route::post('/order-detail/{id}', [AdminOrderController::class, 'detail']);
        Route::delete('/order/{id}', [AdminOrderController::class, 'delete']);
        Route::post('/shop-list-oder', [AdminOrderController::class, 'getlistbyshopid']);

        Route::middleware(['role:3'])->group( function() {
            Route::get('/dashboard', [AdminController::class, 'getDataDashboard']);
            Route::get('/detail-shop', [AdminController::class, 'shop']);
            Route::get('/shop-order', [AdminController::class, 'shopOrder']);
            Route::post('/create-member', [AdminAuthController::class, 'createMember']);
            Route::post('/delete-member', [AdminAuthController::class, 'delete']);
            Route::get('/list-user', [AdminAuthController::class, 'list']);
            Route::post('/detail-user', [AdminAuthController::class, 'detail']);
            Route::post('/edit-member', [AdminAuthController::class, 'update']);
        });
    });
