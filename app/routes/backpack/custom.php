<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::get('admin/register', 'App\Http\Controllers\CustomRegisterController@showRegistrationForm')->name('backpack.auth.register');
Route::post('admin/register', 'App\Http\Controllers\CustomRegisterController@register')->name('backpack.auth.register');

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('engine', 'Engine\EngineCrudController');
    Route::crud('dataset', 'Dataset\DatasetCrudController');
    Route::crud('mydataset', 'Dataset\MyDatasetCrudController');
    Route::crud('purchase', 'Purchase\PurchaseCrudController');
    Route::crud('sale', 'Sales\SalesCrudController');
    Route::crud('api-tutorial', 'Dataset\ApiTutorialCrudController');
    Route::crud('dictionary', 'Dictionary\DictionaryCrudController');
    Route::crud('project', 'Project\ProjectCrudController');
    //Route::crud('dictionary', 'Dataset\MyDatasetCrudController');
}); // this should be the absolute last line of this file
