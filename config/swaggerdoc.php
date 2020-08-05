<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Set Documentation API routes
    |--------------------------------------------------------------------------
    |
    | Here you need to set the routing address and middleware used in the registration.
    | When it is not needed, you can register by yourself.
    |
    */

    'auto_regist_route' => true,

    'paths' => [

        'data' => [

            'path' => '/swaggerdoc/data',

            'middlewares' => []

        ],

        'view' => [

            'path' => '/swaggerdoc',

            'middlewares' => [],

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Set file path
    |--------------------------------------------------------------------------
    |
    | Here you need to set the path of file.
    | Output or read will read the corresponding file.
    |
    */

    'json_path' => 'documentation/api-docs.json',

    'yaml_path' => 'documentation/api-docs.yaml',


    /*
    |--------------------------------------------------------------------------
    | Set openapi 3.0 json base structure
    |--------------------------------------------------------------------------
    |
    | for example from coding.net.
    |
    |   {
    |       "openapi": "3.0.0",
    |       "info": {
    |           "title": "宠物商店 API 文档",
    |           "version": "1.0.0",
    |           "description": "这是一篇关于宠物商店的 DEMO API 文档，仅做参考。",
    |           "contact": {
    |               "name": "CODING",
    |               "url": "https://coding.net",
    |               "email": "support@coding.net"
    |           },
    |           "license": {
    |               "name": "Apache 2.0",
    |               "url": "http://www.apache.org/licenses/LICENSE-2.0.html"
    |           },
    |           "termsOfService": "http://swagger.io/terms/"
    |       },
    |       "servers": [
    |           {
    |               "url": "https://petstore.com/api/v1",
    |               "description": "生产环境"
    |           },
    |           {
    |               "url": "https://petstore.com.test.petstore.com/api/v1",
    |               "description": "测试环境"
    |           }
    |       ],
    |       "paths": [],
    |       "components": {},
    |       "tags": [],
    |   }
    |
    */

    'structure' => [

        'openapi' => '3.0.0',

        'info' => [

            'title' => "API 文档",

            'version' => "1.0.0",

            'description' => "这是关于 this project 的 API 文档",

            'contact' => '',

            'license' => [

                "name" => "Apache 2.0",

                "url" => "http://www.apache.org/licenses/LICENSE-2.0.html",

            ],

            'termsOfService' => '',

        ],

        'servers' => [

            [

                "url" => "https://petstore.com/api/v1",

                "description" => "生产环境",

            ],

            [

                "url" => "https://petstore.com.test.petstore.com/api/v1",

                "description" => "测试环境",

            ],

        ],

        'paths' => [],

        'components' => [],

        'tags' => [],

    ],


    /*
    |--------------------------------------------------------------------------
    | Set standar response
    |--------------------------------------------------------------------------
    |
    */

    'standar_response' => [

        '200' => ['description' => "Request successful"],

        '401' => ['description' => "Unauthorized"],

        '422' => ['description' => "The given data was invalid"],

    ],


    /*
    |--------------------------------------------------------------------------
    | Set default query parameters
    |--------------------------------------------------------------------------
    |
    */

    'query_parameters' => [

        [
            "schema" => [
                "type" => "integer",
                "default" => 20
            ],
            "in" => "query",
            "name" => "page_size",
            "description" => "每页返回数量"
        ],

        [
            "schema" => [
                "type" => "integer",
                "default" => 1
            ],
            "in" => "query",
            "name" => "page",
            "description" => "页码"
        ]

    ]

];
