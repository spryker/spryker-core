<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Propel\Business\PropelDependencyContainer;
use SprykerEngine\Zed\Propel\PropelConfig;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelDependencyContainer
 */
class PropelDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return PropelDependencyContainer
     */
    private function getDependencyContainer()
    {
        $factory = new Factory('Propel');
        $config = new PropelConfig(Config::getInstance(), Locator::getInstance());

        return new PropelDependencyContainer($factory, Locator::getInstance(), $config);
    }

}
