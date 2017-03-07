<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

use Spryker\Shared\Testify\SystemUnderTestBootstrap;

$bootstrap = SystemUnderTestBootstrap::getInstance();

$application = 'Zed';
$bootstrap->bootstrap($application);
