<?php

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Router\RouterConstants;

$config[KernelConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'Spryker',
];

$config[ApplicationConstants::PROJECT_TIMEZONE] = 'UTC';
$config[KernelConstants::PROJECT_NAMESPACE] = 'Pyz';

$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL;

$config[RouterConstants::ROUTER_IS_SSL_ENABLED_YVES] = false;
