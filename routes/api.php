<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\V1\TaskController;
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
Route::post("/register",[AuthController::class,"register"]);
Route::post("/login",[AuthController::class,"login"]);
 


Route::middleware('auth:sanctum')->group(function () {
    Route::get("/logout", [AuthController::class, "logout"]);
    Route::get("/index", [TaskController::class, "index"]);
    Route::post("/insert", [TaskController::class, "store"]);
    Route::put("/update/{task}", [TaskController::class, "update"]);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::delete("/delete/{task}", [TaskController::class, "destroy"]);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});