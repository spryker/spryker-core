<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Spryker\Zed\Application\Business\ApplicationDependencyContainer;

/**
 * @group Spryker
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
    private function getBusinessFactory()
    {
        return new ApplicationDependencyContainer();
    }

    /**
     * @return void
     */
    public function testCreateNavigationBuilderShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getBusinessFactory()->createNavigationBuilder();

        $this->assertInstanceOf('Spryker\Zed\Application\Business\Model\Navigation\NavigationBuilder', $instance);
    }

}
