<?php

$autoloader = function ($className) {

    $className = ltrim($className, '\\');

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
        $filePath = __DIR__ . '/Bundles/';
        $filePath .= $classNameParts[2] . '/src/';
        $filePath .= $className;

        if (file_exists($filePath)) {
            require $filePath;

            return true;
        }
    }

    if (in_array($classNameParts[1], $codeceptionSupportDirectories)) {
        $bundle = array_shift($classNameParts);
        $className = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
        $filePath = __DIR__ . '/Bundles/';
        $filePath .= $bundle . '/tests/_support/';
        $filePath .= $className;

        if (file_exists($filePath)) {
            require $filePath;

            return true;
        }
    }

    if (in_array($classNameParts[0], $testingNamespaces)) {
        if ($classNameParts[0] === 'Acceptance') {
            $bundle = $classNameParts[1];
            $className = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
            $filePath = __DIR__ . '/Bundles/' . $bundle . '/tests/';
            $filePath .= $className;
            if (file_exists($filePath)) {
                require $filePath;

                return true;
            }
        }
        if (in_array($classNameParts[0], ['Functional', 'Unit'])) {
            $bundle = $classNameParts[3];
            $className = implode(DIRECTORY_SEPARATOR, $classNameParts) . '.php';
            $filePath = __DIR__ . '/Bundles/' . $bundle . '/tests/';
            $filePath .= $className;
            if (file_exists($filePath)) {
                require $filePath;

                return true;
            }
        }
    }

    return false;
};

spl_autoload_register($autoloader);
