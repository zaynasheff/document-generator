<?php

return [

    'libreoffice' => [

        'binary' => env(
            'DOCUMENT_GENERATOR_OFFICE_BINARY',
            'soffice'
        ),

        'timeout' => env(
            'DOCUMENT_GENERATOR_TIMEOUT',
            60
        ),
        'profile' => env(
            'DOCUMENT_GENERATOR_OFFICE_PROFILE',
        ),

    ],

];
