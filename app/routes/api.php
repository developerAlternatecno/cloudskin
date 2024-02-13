<?php

use App\Http\Controllers\DatareadController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\EngineController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PSpell\Dictionary;

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
Route::get('/map/datasets', [DatasetController::class, 'getMapDatasets']);
# Engines
Route::post('/engines', [EngineController::class, 'createEngine']);

#Dictionary
Route::get('dictionary/{dictionary_id}',[Dictionary::class.'getDictionary']);
Route::post('dictionary/{dictionary_id}',[Dictionary::class.'addDictionary']);

#Datasets
Route::post('/datasets', [DatasetController::class, 'createDatasetFromAPI']);
Route::post('/datasets/{dataset_id}', [DatasetController::class, 'addDataRead']);
Route::get('/datasets/{dataset_id}', [DatasetController::class, 'getDataReads']);
Route::post('/datasets/{dataset_id}/purchase', [PurchaseController::class, 'createPurchase']);
Route::get('/datasets/{dataset_id}/provider-doc', [DatasetController::class, 'getProviderDoc'])->name('provider-doc');
Route::get('/purchases/{purchase_id}/buyer-doc', [PurchaseController::class, 'getBuyerDoc'])->name('buyer-doc');
Route::post('/datasets/{dataset_id}/bulk-creation', [DatasetController::class, 'bulkCreation']);

Route::post('dataset/{dataset_id}/sale', [SaleController::class, 'createSale']);
Route::get('/purchases/{sale_id}/buyer-doc', [SaleController::class, 'getBuyerDoc'])->name('buyer-doc');