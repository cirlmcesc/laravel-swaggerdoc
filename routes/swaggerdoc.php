<?php

use Illuminate\Support\Facades\Route;

if (config("swagger.auto_regist_route")) {
    Route::middleware(config("swagger.auto_regist_middlewares"))
        ->group(function () {
            Route::get(config("swagger.auto_regist_route_path"), "LaravelSwaggerdocController@json");
        });
}
