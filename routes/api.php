<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GifController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signUp', [AuthController::class, 'signUp']);
});

  
Route::group([
    'middleware' => 'auth:api'
  ], function() {
      Route::get('search',  [GifController::class, 'search']);
      Route::get('searchById',  [GifController::class, 'searchById']);
      Route::post('registerFavorites',  [GifController::class, 'registerFavorites']);
  });
