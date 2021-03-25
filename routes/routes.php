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
        ->name("static.upload");
    Route::resource('/static', "\Paksuco\Statics\Controllers\StaticsController")
        ->names("static");
});

Route::group([
    'layout' => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
    'prefix' => config("paksuco-statics.frontend.route_prefix", ""),
    'middleware' => config("paksuco-statics.backend.middleware.web.guest"),
    'as' => 'paksuco.',
], function () {
    Route::get('/static', "\Paksuco\Statics\Controllers\StaticsController@frontindex")->name("static.home");
});

/*Route::group([
    'prefix' => 'api',
    'middleware' => config("paksuco-statics.backend.middleware.api.guest"),
], function () {
    Route::apiResources([
        'static' => \Paksuco\Statics\API\StaticsItemEndpoint::class,
        'staticcategory' => \Paksuco\Statics\API\StaticsCategoryEndpoint::class,
    ]);
});*/
