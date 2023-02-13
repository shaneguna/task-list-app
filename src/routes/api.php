<?php

use App\Http\Controllers\API\TaskController;
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

Route::prefix('tasks')->group(function () {
    Route::get('/all', [TaskController::class, 'index']);
    Route::post('/add', [TaskController::class, 'store']);
    Route::put('/update', [TaskController::class, 'update']);
});
