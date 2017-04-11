<?php

$autoloader = function ($className) {

    $namespaces = [
        'Spryker'
    ];

    $codeceptionSupportDirectories = [
        'Helper',
        'Module',
    ];

    $testingNamespaces = [
        'SprykerTest',
        'Acceptance',           // old to be removed when all files moved
        'Functional',           // old to be removed when all files moved
        'Unit',                 // old to be removed when all files moved

        'ZedBusiness',          // new-old to be removed when all files moved
        'ZedCommunication',     // new-old to be removed when all files moved
        'ZedPresentation',      // new-old to be removed when all files moved
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
        $filePath = __DIR__ . '/Bundles/' . $bundle . '/src/';
        $filePath .= $className;
    }

    if (in_array($classNameParts[1], $codeceptionSupportDirectories)) {
        $bundle = array_shift($classNameParts);
        $className = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
        $filePath = __DIR__ . '/Bundles/' . $bundle . '/tests/_support/';
        $filePath .= $className;
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
            $filePath = __DIR__ . '/Bundles/' . $bundle . '/tests/';
            $filePath .= $className;
        }
    }
    // This block can completely be removed when all bundles have the new test structure

    // Helper in new structure
    if ($classNameParts[0] === 'SprykerTest') {
        $bundle = $classNameParts[2];
        $filePath = __DIR__ . '/Bundles/' . $bundle . '/tests/SprykerTest/' . $classNameParts[1] . '/' . $classNameParts[2] . '/_support/';
        // Zed's helper directory
        if ($classNameParts[1] === 'Zed') {
            $filePath .= $classNameParts[3] . '/';
        }

        $filePath .= 'Helper/' . array_pop($classNameParts) . '.php';
    }

    if (isset($filePath)) {
        if (file_exists($filePath)) {
            require $filePath;

            return true;
        }
    }

    return false;
};

spl_autoload_register($autoloader);
