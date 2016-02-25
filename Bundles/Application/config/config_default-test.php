<?php

use Spryker\Shared\Application\ApplicationConstants;

$config[ApplicationConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[ApplicationConstants::CORE_NAMESPACES] = [
    'Spryker',
];

$config[ApplicationConstants::PROJECT_TIMEZONE] = 'UTC';
$config[ApplicationConstants::PROJECT_NAMESPACE] = 'Pyz';

$config[ApplicationConstants::ERROR_LEVEL] = E_ALL;

/* Customer */
$config[ApplicationConstants::APPLICATION_SPRYKER_ROOT] = APPLICATION_ROOT_DIR . '/../';
$config[ApplicationConstants::ZED_TWIG_OPTIONS] = [
    'cache' => \Spryker\Shared\Library\DataDirectory::getLocalStoreSpecificPath('cache/Zed/twig'),
];

// Why this?
$config[ApplicationConstants::YVES_SSL_ENABLED] = false;
$config[ApplicationConstants::NAVIGATION_CACHE_ENABLED] = false;

$config[ApplicationConstants::HOST_YVES] = 'www.spryker.dev';
$config[ApplicationConstants::NAVIGATION_ENABLED] = false;


