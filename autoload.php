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
        'Acceptance',
        'Functional',
        'Unit',
        'ZedBusiness',
        'ZedCommunication',
        'ZedPresentation',
        'Yves',
        'Client',
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

    if (isset($filePath)) {
        if (file_exists($filePath)) {
            require $filePath;

            return true;
        }
    }

    return false;
};

spl_autoload_register($autoloader);
