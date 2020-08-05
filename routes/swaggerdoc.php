<?php

use Illuminate\Support\Facades\Route;

if (config("swagger.auto_regist_route")) {
    Route::middleware(config("swagger.paths.data.middlewares"))
        ->group(function () {
            Route::get(config("swagger.paths.data.path"), "LaravelSwaggerdocController@json");
        });

    Route::middleware(config("swagger.paths.view.middlewares"))
        ->group(function () {
            Route::get(config("swagger.paths.view.show"), "LaravelSwaggerdocController@view");
        });
}
