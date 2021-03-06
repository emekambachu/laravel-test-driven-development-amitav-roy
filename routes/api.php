<?php

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
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/auth', [LoginController::class, 'handleLogin'])->name('user.login');

Route::group(['middleware' => ['auth:sanctum']], static function(){
    Route::post('/video/add', [VideoController::class, 'store'])
        ->name('video.add');
    Route::get('/video/list', [VideoController::class, 'index'])
        ->name('video.list');
});
