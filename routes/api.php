<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\ProductController;
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

require __DIR__ . '/auth.php';

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v1'], function () {
    Route::post('upload-image', ImageUploadController::class);

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'list']);
        Route::middleware('admin')->post('/', [ProductController::class, 'save']);
        Route::get('/{product}', [ProductController::class, 'item']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [CartController::class, 'list']);
        Route::post('/', [CartController::class, 'save']);
        Route::delete('/{product}', [CartController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'list']);
        Route::post('/', [CategoryController::class, 'save']);
        Route::get('/{category}', [CategoryController::class, 'item']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });
});
