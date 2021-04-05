<?php

use Illuminate\Support\Facades\Route;
use Paksuco\Statics\Controllers\StaticsBaseCategoryController;
use Paksuco\Statics\Controllers\StaticsCategoryController;
use Paksuco\Statics\Controllers\StaticsController;

// admin routes
Route::group([
    'layout' => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
    'prefix' => config("paksuco-statics.backend.admin_route_prefix", ""),
    'middleware' => config("paksuco-statics.backend.middleware.web.auth"),
    'as' => 'paksuco-statics.'
], function () {
    Route::resource('static-categories', StaticsBaseCategoryController::class)->only("show")->names("category.base");
    Route::resource('static-categories.categories', StaticsCategoryController::class)->except("show")->names("category");
    Route::resource('static-categories.items', StaticsController::class)->names("category.items");
    Route::post("/static/upload", "\Paksuco\Statics\Controllers\StaticsController@upload")->name("upload");
});

Route::group([
    'layout' => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
    'prefix' => config("paksuco-statics.frontend.route_prefix", ""),
    'middleware' => config("paksuco-statics.backend.middleware.web.guest"),
    'as' => 'paksuco-statics.',
], function () {
    Route::get('category/{category}', [StaticsCategoryController::class, 'frontindex'])->name("category.frontshow");
    Route::get('page/{item}', [StaticsController::class, 'frontshow'])->name("category.items.frontshow");
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
