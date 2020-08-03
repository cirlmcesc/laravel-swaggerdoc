<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('responseHeader')) {
    /**
     * responseHeader function
     *
     * @param JsonResponse $response
     * @return JsonResponse
     */
    function responseHeader(JsonResponse $response): JsonResponse
    {
        return $response->withHeaders([
            'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, X-Api-Token, X-Requested-With, Content-Type:application/json, Accept',
            'Access-Control-Allow-Credentials' => 'true',
        ]);
    }
}
