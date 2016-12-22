<?php

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;

$config[ApplicationConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[ApplicationConstants::CORE_NAMESPACES] = [
    'Spryker',
];

$config[ApplicationConstants::PROJECT_TIMEZONE] = 'UTC';
$config[ApplicationConstants::PROJECT_NAMESPACE] = 'Pyz';

$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL;

$config[ApplicationConstants::ZED_TWIG_OPTIONS] = [
    'cache' => APPLICATION_ROOT_DIR . '/data/DE/cache/Zed/twig',
];

$config[ApplicationConstants::YVES_SSL_ENABLED] = false;
