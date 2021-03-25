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
    Route::resource('/static/categories', "\Paksuco\Static\Controllers\StaticCategoryController")
        ->names("staticcategory");
    Route::post("/static/upload", "\Paksuco\Static\Controllers\StaticController@upload")
        ->name("static.upload");
    Route::resource('/static', "\Paksuco\Static\Controllers\StaticController")
        ->names("static");
});

Route::group([
    'layout' => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
    'prefix' => config("paksuco-statics.frontend.route_prefix", ""),
    'middleware' => config("paksuco-statics.backend.middleware.web.guest"),
    'as' => 'paksuco.',
], function () {
    Route::get('/static', "\Paksuco\Static\Controllers\StaticController@frontindex")->name("static.home");
});

/*Route::group([
    'prefix' => 'api',
    'middleware' => config("paksuco-statics.backend.middleware.api.guest"),
], function () {
    Route::apiResources([
        'static' => \Paksuco\Support\API\StaticItemEndpoint::class,
        'staticcategory' => \Paksuco\Static\API\StaticCategoryEndpoint::class,
    ]);
});*/
