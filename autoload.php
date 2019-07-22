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
        'Acceptance',           // old, to be removed when all files moved
        'Functional',           // old, to be removed when all files moved
        'Unit',                 // old, to be removed when all files moved

        'Yves',                 // new-old to be removed when all files moved
        'Client',               // new-old to be removed when all files moved
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

    if (in_array($classNameParts[0], $namespaces)) {
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

    // This block can completely be removed when all bundles have the new test structure
    if (in_array($classNameParts[0], $testingNamespaces)) {
        if ($classNameParts[0] === 'Acceptance') {
            $bundle = $classNameParts[1];
        }
        if (in_array($classNameParts[0], ['Functional', 'Unit'])) {
            $bundle = $classNameParts[3];
        }

        if (isset($bundle)) {
            $className = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
            $filePathParts = [
                __DIR__,
                'Bundles',
                $bundle,
                'tests',
                $className,
            ];
        }
    }
    // This block can completely be removed when all bundles have the new test structure

    // Works for classes under _support subdirectory in new structure
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
            include($filePath);

            return true;
        }

        if (isset($filePathPartsHelper)) {
            $filePath = implode(DIRECTORY_SEPARATOR, $filePathPartsHelper);
            if (file_exists($filePath)) {
                include($filePath);

                return true;
            }
        }
    }

    return false;
};

spl_autoload_register($autoloader);
