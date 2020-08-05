<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Cirlmcesc\LaravelSwaggerdoc\LaravelSwaggerdoc;

class LaravelSwaggerdocController extends Controller
{
    /**
     * json function
     *
     * @return JsonResponse
     */
    public function json(LaravelSwaggerdoc $documentor): JsonResponse
    {
        return responseHeader(response()->json($documentor->readJsonFile()));
    }

    /**
     * show function
     *
     * @return View
     */
    public function show(): View
    {
        return view("swaggerdoc::swaggerdocui", [
            "json_path" => config(""),
        ]);
    }
}
