<?php

use Illuminate\Support\Facades\Route;

/**
 * Routes for the package would go here
 */

Route::group([
    'layout' => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
    'prefix' => config("paksuco-statics.backend.admin_route_prefix", ""),
    'middleware' => config("paksuco-statics.backend.middleware.web.auth"),
    'as' => 'paksuco.',
], function () {
    Route::resource('/static/categories', "\Paksuco\Statics\Controllers\StaticsCategoryController")
        ->names("staticcategory");
    Route::post("/static/upload", "\Paksuco\Statics\Controllers\StaticsController@upload")
        ->name("statics.upload");
    Route::resource('/static', "\Paksuco\Statics\Controllers\StaticsController")->except(["show"])
        ->names("statics");
});

Route::group([
    'layout' => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
    'prefix' => config("paksuco-statics.frontend.route_prefix", ""),
    'middleware' => config("paksuco-statics.backend.middleware.web.guest"),
    'as' => 'paksuco.',
], function () {
    Route::get('/categories', "\Paksuco\Statics\Controllers\StaticsCategoryController@frontindex")->name("staticcategory.home");
    Route::get('/category/{category}', "\Paksuco\Statics\Controllers\StaticsCategoryController@frontshow")->name("staticcategory.frontshow");
    Route::get('/pages', "\Paksuco\Statics\Controllers\StaticsController@frontindex")->name("statics.home");
    Route::get('/pages/{static}', "\Paksuco\Statics\Controllers\StaticsController@frontshow")->name("statics.frontshow");
});

Route::group([
    'prefix' => 'api',
    'middleware' => config("paksuco-statics.backend.middleware.api.guest"),
], function () {
    Route::apiResources([
        'statics' => \Paksuco\Statics\API\StaticsItemEndpoint::class,
        'staticcategory' => \Paksuco\Statics\API\StaticsCategoryEndpoint::class,
    ]);
});
