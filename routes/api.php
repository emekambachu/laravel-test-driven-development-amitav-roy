<?php

use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/login', [LoginController::class, 'handleLogin'])
    ->name('user.login');

Route::group(['middleware' => 'auth:sanctum'], static function () {
    Route::get('/video', [VideoController::class, 'index']);
    Route::post('/video/add', [VideoController::class, 'store']);

    // Admin routes
    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], static function () {
        Route::get('/video/list/unpublished', [AdminVideoController::class, 'unpublished']);
    });
});

