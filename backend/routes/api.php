<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SomeController;

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

//公共路由无需认证
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-code', [AuthController::class, 'sendCode']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/captcha', [AuthController::class, 'generateCaptcha']);

//需要认证的路由
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('forms', FormController::class);
    Route::apiResource('processes', ProcessController::class);
    Route::post('/processes/{process}/start', [ProcessController::class, 'startInstance']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboards', function (){return 'dashboards';});
});

Route::prefix('users')->group(function () {
    Route::get('', [UserController::class, 'index']);
    Route::post('', [UserController::class,'store']);
    Route::get('/{user}', [UserController::class,'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/protected-route', [SomeController::class, 'protectedMethod']);
});
