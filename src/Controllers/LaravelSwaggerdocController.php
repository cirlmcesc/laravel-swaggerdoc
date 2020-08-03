<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
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
}
