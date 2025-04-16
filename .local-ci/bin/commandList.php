<?php

/**
 * This file configures the commands that are available in the local CI environment.
 *
 * The key of the array is the name of the command. F.e. 'CodeStyleSniffer' which contains the configuration for the PHPCodeSniffer.
 *
 * The following options are available:
 * - hasModule: bool - Indicates if the command has the module name in the `command` configuration. The command name can either be at the end and only needs to be added, or it can be somewhere in the command definition. In the latter case, the `moduleArgument` option will be added to the command using sprintf().
 * - hasPath: bool - Indicates if the command has a path definition in the `command` configuration. The path of the module will be added to the last element in the command using sprintf().
 * - isShellCommand: bool - Indicates that the underlying Symfony Process should be constructed with Process::fromShellCommand(). This is needed for commands that are using key=value options.
 * - hasModuleInArgument: bool - Indicates if the module name is part of the command arguments. If this is set to true, the `moduleArgument` option will be added to the command using sprintf().
 * - organization: string - The organization name that is used in the command. This is used to determine if the current command can run on the current module.
 * - moduleArgument: int - The position of the module name in the command arguments. This is used to determine to which entry in the command definition array the module name should be added.
 * - useFullName: bool - Indicates if the full module name (OrganizationName.ModuleName) should be used in the command. If this is set to false, only the module name will be used.
 * - command: array - The command that should be executed. The command can contain placeholders for the module name. If the `hasModule` option is set to true, the module name will be added to the command using sprintf(). The command array will be used in the Symfony Process and is the same command you would run in CLI but split into an array. E.g. `console do:something` has to be expressed as ['console', 'do:something'].
 */

return [
    'NpmFormatter' => [
        'hasModule' => false,
        'hasFixCommand' => true,
        'hasPath' => true,
        'isShellCommand' => true,
        'requiresPath' => true,
        'command' => [
            'npm run formatter --path=%s',
        ],
        'fixCommand' => [
            'npm run formatter:fix --path=%s',
        ],
    ],
    'CodeStyleSniffer' => [
        'hasModule' => true,
        'hasFixCommand' => true,
        'command' => [
            'vendor/bin/console',
            'c:s:s',
            '-m',
        ],
        'fixCommand' => [
            'vendor/bin/console',
            'c:s:s',
            '-f', // Runs the fix command of the CS fixer
            '-m',
        ],
    ],
    'PHPStan' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/console',
            'code:phpstan',
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
    'ValidatePropelAbstractClass' => [
        'hasModule' => true,
        'command' => [
            'vendor/bin/console',
            'code:propel:validate-abstract',
            '-vv',
            '-m',
        ],
    ],
    'TestingSpryker' => [
        'hasModule' => true,
        'hasModuleInArgument' => true,
        'moduleArgument' => 3,
        'organization' => 'Spryker',
        'useFullName' => false,
        'command' => [
            'vendor/bin/codecept',
            'run',
            '-c',
            'vendor/spryker/spryker/Bundles/%s',
        ],
    ],
    'TestingSprykerShop' => [
        'hasModule' => true,
        'hasModuleInArgument' => true,
        'moduleArgument' => 3,
        'organization' => 'SprykerShop',
        'useFullName' => false,
        'command' => [
            'vendor/bin/codecept',
            'run',
            '-c',
            'vendor/spryker/spryker-shop/Bundles/%s',
        ],
    ],
];
