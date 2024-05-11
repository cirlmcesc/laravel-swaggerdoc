<?php

use Illuminate\Support\Arr;

return [

    'auto_regist_route' => true,

    'file_directory' => 'documentations',

    'backup_directory' => 'backups',

    'backup_directory_nomenclature' => fn (string $previous_version): string =>
        Arr::join(array: [now()->format('YmdHis'), $previous_version], glue: '-'),
    

    /*
    |--------------------------------------------------------------------------
    | Preset data
    |--------------------------------------------------------------------------
    |
    | Used to generate properties set in advance for current events.
    | OpenAPI Specification: https://swagger.io/specification/
    |
    */

    'preset_value' => [

        /**
         * REQUIRED. This string MUST be the version number of the OpenAPI 
         * Specification that the OpenAPI document uses. The openapi field SHOULD
         * be used by tooling to interpret the OpenAPI document. This is not related
         * to the API info.version string.
         */
        'openapi' => '3.1.0',

        /**
         * REQUIRED. Provides metadata about the API.
         * The metadata MAY be used by tooling as required.
         */
        'info' => [

            /** REQUIRED. The title of the API. */
            'title' => env('APP_NAME', '') . 'API Documentation',

            /** A short summary of the API. */
            // "summary" => env('APP_NAME', '') . 'API Documentation Summary',
            
            /** 
             * A description of the API. CommonMark syntax MAY
             * be used for rich text representation.
             */
            'description' => env('APP_NAME', '') . 'API Documentation Description',

            /** 
             * REQUIRED. The version of the OpenAPI document
             * (which is distinct from the OpenAPI Specification version or the 
             * API implementation version).
             */
            'version' => '0.0.1',

            /**
             * The contact information for the exposed API.
             */
            // "contact" => [
            //     "name" => "API Support",
            //     "url" => "http://www.example.com/support",
            //     "email" => "support@example.com",
            // ],

            /**
             * The license information for the exposed API.
             */
            // "license" => [
            //     "name" => 'Apache 2.0',
            //     "url" => "http://www.apache.org/licenses/LICENSE-2.0.html",
            // ],

            /**
             * A URL to the Terms of Service for the API. This MUST be in the form of a URL.
             */
            // "termsOfService" => "http://swagger.io/terms/",

        ],

        /**
         * An array of Server Objects, which provide connectivity information
         * to a target server. If the servers property is not provided,
         * or is an empty array, the default value would be a Server Object
         * with a url value of /.
         */
        'servers' => [

            /**
             * A single server would be described as:
             */
            [
                "url" => env('APP_URL', "http://development-api.example.com"),
                "description" => env('APP_ENV', 'Development') . " server",
            ],
            // [
            //     "url" => "https://staging-api.example.com/v1",
            //     "description" => "Staging server"
            // ],
            // [
            //     "url" => "http://api.example.com/v1",
            //     "description" => "Production server",
            // ],

        ],

        /**
         * An element to hold various schemas for the document.
         */
        'components' => [

            'schemas' => [],

            'parameters' => [],

            'responses' => [],

            'securitySchemes' => [],

            'examples' => [],

            'requestBodies' => [],

            'headers' => [],

            'links' => [],

            'callbacks' => [],

        ],

        /**
         * A list of tags used by the document with additional metadata.
         * The order of the tags can be used to reflect on their order
         * by the parsing tools. Not all tags that are used by theOperation Object
         * must be declared. The tags that are not declared MAY be organized randomly
         *  or based on the tools' logic. Each tag name in the list MUST be unique.
         */
        "tags" => [],

        /**
         * The available paths and operations for the API.
         */
        'paths' => [],

    ],

];
