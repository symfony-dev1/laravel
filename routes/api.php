<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;

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

// auth routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('posts', PostController::class);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout',  [AuthController::class, 'logout']);

    Route::apiResource('checkout', CheckoutController::class);
    Route::apiResource('orders', OrderController::class);
});

// login - register routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});

// products route
Route::get('product/{slug}', [ProductController::class, "getBySlug"]);
Route::post('product/checkVariantAvail', [ProductController::class, "checkVariantAvail"]);

Route::apiResource('products', ProductController::class);

// carts route
Route::post('cart/onChangeQuantity', [CartController::class, "onChangeQuantity"]);
Route::post('cart/cartCalculation', [CartController::class, "cartCalculation"]);
Route::post('cart/cartGlobalCount', [CartController::class, "cartGlobalCount"]);
Route::post('cart/removeItemCart', [CartController::class, "removeItemCart"]);
Route::apiResource('cart', CartController::class);
