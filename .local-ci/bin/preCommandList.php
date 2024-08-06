<?php

return [
    'TransferGenerate' => [
        'command' => [
            'vendor/bin/console',
            'transfer:generate',
        ],
    ],
    'DatabuilderGenerate' => [
        'command' => [
            'vendor/bin/console',
            'transfer:databuilder:generate',
        ],
    ],
];
