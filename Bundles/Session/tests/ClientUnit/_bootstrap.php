<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
$bootstrap = Spryker\Shared\Library\SystemUnderTest\SystemUnderTestBootstrap::getInstance();

$application = 'Client';
$bootstrap->bootstrap($application);
