<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
Route::middleware('auth:api')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // TODO: API naming convention should contain a version number.
    // Change the APIs path to be something like this `/api/v1/products`.

    // Gets all the products.
    Route::get('/products', [ProductController::class, 'index']);
    // Fetch one product.
    Route::get('/products/{id}', [ProductController::class, 'show']);
    // Create a product.
    Route::post('/products', [ProductController::class, 'store']);
    // Update a product.
    Route::put('/products/{id}', [ProductController::class, 'update']);
    // Delete a product.
    Route::delete('/products/{id}', [ProductController::class, 'delete']);
});
