<?php

return [

    'libreoffice' => [

        'command' => env(
            'DOCUMENT_GENERATOR_OFFICE_COMMAND',
            'soffice'
        ),

        'timeout' => env(
            'DOCUMENT_GENERATOR_TIMEOUT',
            60
        ),

    ],

];
