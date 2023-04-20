<?php

use App\Http\Controllers\DatareadController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\PurchaseController;
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

#Map
Route::get('/map/datareads', [DatareadController::class, 'getMapDatareads']);

# Engines
Route::post('/engines', [EngineController::class, 'createEngine']);

#Sinks
Route::post('/datasets', [DatasetController::class, 'createDatasetFromAPI']);
Route::post('/datasets/{dataset_id}', [DatasetController::class, 'addDataRead']);
Route::get('/datasets/{dataset_id}', [DatasetController::class, 'getDataReads']);
Route::post('/datasets/{dataset_id}/purchase', [PurchaseController::class, 'createPurchase']);
