<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */


$bootstrap = SprykerFeature\Shared\Library\SystemUnderTest\SystemUnderTestBootstrap::getInstance();

$application = 'Yves';
$bootstrap->bootstrap($application);
