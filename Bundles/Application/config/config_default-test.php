<?php

use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\Kernel\KernelConstants;

$config[KernelConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'Spryker',
];

$config[KernelConstants::PROJECT_NAMESPACE] = 'Pyz';

$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL;
