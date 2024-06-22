<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 



//login Register Route
//public route
Route::get('/user', [UserController::class, 'index']);
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/reset', [PasswordResetController::class, 'sendResetLinkEmail']);

//private route

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::post('/user/changePass', [UserController::class, 'change_password']);
     //Simple Crud Routes
  Route::get('/post', [PostController::class, 'index']);
  Route::post('/post', [PostController::class, 'store']);
  Route::get('/post/{id}/show', [PostController::class, 'show']);
  Route::post('post/{id}/edit', [PostController::class, 'update']);
  Route::delete('post/{id}/delete', [PostController::class, 'destroy']);

  
});
