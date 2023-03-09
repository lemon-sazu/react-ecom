<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontEndController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('get-category', [FrontEndController::class, 'category']);
Route::get('get-product-by-category/{slug}', [FrontEndController::class, 'product_by_cat']);
Route::get('single-product/{slug}', [FrontEndController::class, 'singleProduct']);
Route::post('add-to-cart', [CartController::class, 'addToCart']);
Route::get('cart', [CartController::class, 'cart']);
Route::put('cart-update/{id}/{scop}', [CartController::class, 'cartUpdate']);
Route::delete('cart-delete/{id}', [CartController::class, 'cartDelete']);
Route::post('order-user-info', [CheckoutController::class, 'orderplace']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::get('/checkingauthenticated', function () {
        return response()->json([
            'status' => 200, 'message' => 'You are admin'
        ]);
    });
    // product 
    Route::get('view-product', [ProductController::class, 'index']);
    Route::get('view-product/{id}', [ProductController::class, 'edit']);
    Route::post('update-product/{id}', [ProductController::class, 'update']);
    Route::post('store-product', [ProductController::class, 'store']);
    //category 
    Route::get('view-category', [CategoryController::class, 'index']);
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::put('update-category/{id}', [CategoryController::class, 'update']);
    Route::post('store-category', [CategoryController::class, 'store']);
    Route::delete('category-delete/{id}', [CategoryController::class, 'delete']);
    Route::get('get-category', [CategoryController::class, 'get_category']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});
