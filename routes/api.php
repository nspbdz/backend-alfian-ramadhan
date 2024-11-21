<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Routes for merchants
    Route::middleware('role:merchant')->group(function () {
        Route::post('/product', [ProductController::class, 'create']);
        Route::get('/my-product', [ProductController::class, 'byMerchant']);
        Route::delete('/product/{id}', [ProductController::class, 'delete']);
        Route::post('/product/{id}', [ProductController::class, 'update']);
        Route::get('/merchant/customers', [TransactionController::class, 'viewCustomers']);
    });

    // Routes for customers
    Route::middleware('role:customer')->group(function () {
        Route::post('/cart/add', [CartController::class, 'addToCart']);
        Route::get('/cart/view', [CartController::class, 'viewCart']);
        Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
        Route::post('/cart/update/{id}', [CartController::class, 'updateCartItem']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/transaction', [TransactionController::class, 'createTransaction']);
        Route::get('/my-tansaction', [TransactionController::class, 'viewTransactions']);
    });


});