<?php

use Spryker\Shared\Library\Autoloader;

$vendor = __DIR__ . '/../Bundles';
if (!is_dir($vendor)) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL;
    exit(1);
}

require_once $vendor . '/Library/src/Spryker/Shared/Library/Autoloader.php';

$bundleParent = realpath($vendor . '/..');
$vendor = realpath($bundleParent . '/../..');

Autoloader::register($bundleParent, $vendor);
