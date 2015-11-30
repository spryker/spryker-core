<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Development\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Development\Business\DevelopmentDependencyContainer;
use SprykerFeature\Zed\Development\DevelopmentConfig;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Development
 * @group Business
 * @group DevelopmentDependencyContainer
 */
class DevelopmentDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return DevelopmentDependencyContainer
     */
    private function getDependencyContainer()
    {
        $factory = new Factory('Development');
        $config = new DevelopmentConfig(Config::getInstance(), Locator::getInstance());

        return new DevelopmentDependencyContainer($factory, Locator::getInstance(), $config);
    }

}
