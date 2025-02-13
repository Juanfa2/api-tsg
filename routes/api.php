<?php

use App\Http\Controllers\api\JWTAuthController;
use App\Http\Controllers\api\PostsController;
use App\Http\Controllers\api\UsersController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[JWTAuthController::class,'register']);
Route::post('login',[JWTAuthController::class,'login']);

Route::middleware([JWTMiddleware::class])->group(function () {
    Route::post('logout',[JWTAuthController::class,'logout']);

    /*Users*/
    Route::apiResource('/users',UsersController::class)
        ->only(['store','destroy'])
        ->middleware(AdminMiddleware::class);

    Route::get('/users', [UsersController::class, 'index']);
    Route::get('/users/{user}', [UsersController::class, 'show']);
    Route::put('/users/{user}', [UsersController::class, 'update']);

    /*Posts*/
    Route::apiResource('/posts', PostsController::class);

    Route::get('/posts/user/{user}',[PostsController::class,'getPostsByUser']);
});

