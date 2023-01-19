<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['guest:sanctum']], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::get('/posts', [PostController::class, 'index']);
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}/update', [PostController::class, 'update']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
