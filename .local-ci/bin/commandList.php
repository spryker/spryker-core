<?php

return [
    'CodeStyleSniffer' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/console',
            'c:s:s',
            '-m',
        ],
    ],
    'ArchitectureSniffer' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/console',
            'code:sniff:architecture',
            '-m',
        ],
    ],
    'Update JSON files' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/console',
            'dev:composer:update-json-files',
        ],
    ],
    'Validate JSON files' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/console',
            'dev:composer:validate-json-files',
        ],
    ],
    'DependencyFinder' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/spryker-dev-console',
            'dev:dependency:find',
        ],
    ],
    'ValidateDataBuilderSchema' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/spryker-dev-console',
            'dev:validate-module-databuilders',
            '-m',
        ],
    ],
    'ValidateTransferSchema' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/spryker-dev-console',
            'dev:validate-module-transfers',
            '-m',
        ],
    ],
    'ValidatePropelSchemas' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/spryker-dev-console',
            'dev:validate-module-schemas',
            '-vv',
            '-m',
        ],
    ],
    'Testing' => [
        'hasModule' => true,
        'hasModuleInArgument' => true,
        'moduleArgument' => 3,
        'useFullName' => false,
        'command' => [
            'vendor/bin/codecept',
            'run',
            '-c',
            'vendor/spryker/spryker/Bundles/%s',
        ],
    ],
];
