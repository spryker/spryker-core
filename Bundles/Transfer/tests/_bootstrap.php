<?php

require_once __DIR__ . '/../../../autoload.php';

\SprykerFeature\Shared\Library\Autoloader::unregister();
$bootstrap = SprykerFeature\Shared\Library\SystemUnderTest\SystemUnderTestBootstrap::getInstance();
$bootstrap->bootstrap('Zed');
