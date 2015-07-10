<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Application\ApplicationConfig;
use SprykerFeature\Zed\Application\Business\ApplicationDependencyContainer;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ApplicationFacade
 * @group Business
 * @group ApplicationFacadeDependencyContainer
 */
class ApplicationDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ApplicationDependencyContainer
     */
    private function getDependencyContainer()
    {
        $factory = new Factory('Application');
        $config = new ApplicationConfig(Config::getInstance(), Locator::getInstance());

        return new ApplicationDependencyContainer($factory, Locator::getInstance(), $config);
    }

    public function testCreateNavigationBuilderShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createNavigationBuilder();

        $this->assertInstanceOf('SprykerFeature\Zed\Application\Business\Model\Navigation\NavigationBuilder', $instance);
    }

}
