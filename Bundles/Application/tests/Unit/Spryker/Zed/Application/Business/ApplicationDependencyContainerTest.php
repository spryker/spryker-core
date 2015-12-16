<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Spryker\Zed\Application\Business\ApplicationBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group ApplicationFacade
 * @group Business
 * @group ApplicationFacadeBusinessFactory
 */
class ApplicationBusinessFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ApplicationBusinessFactory
     */
    private function getBusinessFactory()
    {
        return new ApplicationBusinessFactory();
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
