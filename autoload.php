<?php

$autoloader = function ($className) {

    $namespaces = [
        'Spryker',
    ];

    $codeceptionSupportDirectories = [
        'Helper',
        'Module',
    ];

    $testingNamespaces = [
        'SprykerTest',
    ];

    $applicationAccessRules = [
        'ZED' => [
            'Shared',
            'Client',
            'Service',
            'Zed',
        ],
        'YVES' => [
            'Shared',
            'Client',
            'Service',
            'Yves',
        ],
    ];

    $className = ltrim($className, '\\');
    $classNameParts = explode('\\', $className);

    if (count($classNameParts) < 3) {
        return false;
    }

    if (!in_array($classNameParts[0], $namespaces)
        && !in_array($classNameParts[1], $codeceptionSupportDirectories)
        && !in_array($classNameParts[0], $testingNamespaces)) {
        return false;
    }

    $validateApplicationAccess = function (array $classNameParts) use ($applicationAccessRules) {
        if (!defined('APPLICATION') || !isset($applicationAccessRules[APPLICATION])) {
            return;
        }
        $allowedApplications = $applicationAccessRules[APPLICATION];
        $application = $classNameParts[1];
        if (in_array($application, $allowedApplications)) {
            return;
        }

        throw new Exception(sprintf('Failed to load "%s", it is not allowed to access "%s" inside "%s".', implode('\\', $classNameParts), $application, ucfirst(strtolower(APPLICATION))));
    };

    // File in Spryker\\ namespace
    if (in_array($classNameParts[0], $namespaces)) {
        $validateApplicationAccess($classNameParts);
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        $bundle = $classNameParts[2];
        $filePathParts = [
            __DIR__,
            'Bundles',
            $bundle,
            'src',
            $className,
        ];
    }

    // File in Helper/Module namespace of codeception
    if (in_array($classNameParts[1], $codeceptionSupportDirectories)) {
        $bundle = array_shift($classNameParts);
        $className = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
        $filePathParts = [
            __DIR__,
            'Bundles',
            $bundle,
            'tests',
            '_support',
            $className,
        ];
    }

    // Helper in new structure
    if ($classNameParts[0] === 'SprykerTest') {
        $bundle = $classNameParts[2];
        $rest = array_slice($classNameParts, 3);
        $className = implode(DIRECTORY_SEPARATOR, $rest) . '.php';
        $filePathParts = [
            __DIR__,
            'Bundles',
            $bundle,
            'tests',
            'SprykerTest',
            $classNameParts[1],
            $classNameParts[2],
            $className,
        ];
        $filePathPartsHelper = [
            __DIR__,
            'Bundles',
            $bundle,
            'tests',
            'SprykerTest',
            $classNameParts[1],
            $classNameParts[2],
            '_support',
            $className,
        ];
    }

    if (isset($filePathParts)) {
        $filePath = implode(DIRECTORY_SEPARATOR, $filePathParts);
        if (file_exists($filePath)) {
            require $filePath;

            return true;
        }

        if (isset($filePathPartsHelper)) {
            $filePath = implode(DIRECTORY_SEPARATOR, $filePathPartsHelper);
            if (file_exists($filePath)) {
                require $filePath;

                return true;
            }
        }
    }

    return false;
};

spl_autoload_register($autoloader);
