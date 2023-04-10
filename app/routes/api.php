<?php

use App\Http\Controllers\EngineController;
use App\Http\Controllers\SinkController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



# Engines
Route::post('/engines', [EngineController::class, 'createEngine']);

#Sinks
Route::post('/sinks', [SinkController::class, 'createSink']);
Route::post('/sinks/{sink_id}', [SinkController::class, 'addDataRead']);
