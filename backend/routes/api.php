<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\EntityController;
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
    Route::get('/dashboards', function () {
        return 'dashboards';
    });
});

Route::prefix('users')->group(function () {
    Route::get('', [UserController::class, 'index']);
    Route::post('', [UserController::class, 'store']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/protected-route', [SomeController::class, 'protectedMethod']);
});

//分类数据管理
Route::prefix('classification')->group(function () {
// 分类项
    Route::get('/', [ClassificationController::class, 'index']);
    Route::post('/store', [ClassificationController::class, 'store']);
    Route::put('/project/{id}', [ClassificationController::class, 'update']);
    Route::delete('/project/{id}', [ClassificationController::class, 'delete']);

// 分类内容
    Route::get('/content/{dataId}/{parentId?}', [ClassificationController::class, 'getClassificationData']);
    Route::post('/content', [ClassificationController::class, 'addClassificationData']);
    Route::put('/content/{id}', [ClassificationController::class, 'updateClassificationData']);
    Route::delete('/content/{id}', [ClassificationController::class, 'deleteClassificationData']);
});

//业务实体管理
Route::prefix('entity')->group(function () {
    Route::get('/', [EntityController::class, 'index'])->name('index');       // 获取所有实体
    Route::post('/', [EntityController::class, 'store'])->name('store');     // 创建新实体
    Route::get('/{id}', [EntityController::class, 'show'])->name('show');    // 获取指定实体
    Route::put('/{id}', [EntityController::class, 'update'])->name('update');// 更新指定实体
    Route::delete('/{id}', [EntityController::class, 'destroy'])->name('destroy'); // 删除指定实体
    //字段管理
    Route::get('/{id}/fields', [EntityController::class, 'getFields'])->name('getFields');
    Route::post('/{id}/fields', [EntityController::class, 'addField'])->name('addField');
    Route::put('/{id}/fields/{fieldId}', [EntityController::class, 'updateField'])->name('updateField');
    Route::delete('/{id}/fields/{fieldId}', [EntityController::class, 'deleteField'])->name('deleteField');
    Route::post('/{id}/fields/sort', [EntityController::class, 'sortFields'])->name('sortFields');
    Route::post('/{id}/fields/move', [EntityController::class, 'moveField'])->name('moveField');
});
