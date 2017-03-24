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

    $classNameParts = explode('\\', $className);

    if (count($classNameParts) < 3) {
        return false;
    }

    if (!in_array($classNameParts[0], $namespaces) && !in_array($classNameParts[1], $codeceptionSupportDirectories)) {
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

    return false;
};

spl_autoload_register($autoloader);
