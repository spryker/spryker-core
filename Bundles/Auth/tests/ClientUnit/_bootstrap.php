<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
$bootstrap = SprykerFeature\Shared\Library\SystemUnderTest\SystemUnderTestBootstrap::getInstance();

$application = 'Client';
$bootstrap->bootstrap($application);
