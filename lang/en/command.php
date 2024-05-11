<?php

use function Laravel\Prompts\progress;

return [

    'make' => [

        'file_name' => [

            'required' => "What is the documentation name?"

        ],
        'file_type' => [

            'label' => "What file type needs to be generated?",
            'required' => "Must select at least one type."

        ],

    ],

    'generate' => [

        'progress' => [

            'label' => 'Generating Swagger Documentation',
            'finish' => 'Generate Documentation successfully !',

        ],

        'structure' => [

            'info' => [

                'title' => 'What is the title of the API documentation?',
                'description' => 'What is the description of the API documentation?',

            ],

        ],

    ],

];