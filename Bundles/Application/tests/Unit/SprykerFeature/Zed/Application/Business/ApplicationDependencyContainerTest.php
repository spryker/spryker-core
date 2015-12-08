<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

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
        return new ApplicationDependencyContainer();
    }

    /**
     * @return void
     */
    public function testCreateNavigationBuilderShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createNavigationBuilder();

        $this->assertInstanceOf('SprykerFeature\Zed\Application\Business\Model\Navigation\NavigationBuilder', $instance);
    }

}
