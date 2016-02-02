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
     * @return \Spryker\Zed\Application\Business\ApplicationBusinessFactory
     */
    private function getFactory()
    {
        return new ApplicationBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreateNavigationBuilderShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getFactory()->createNavigationBuilder();

        $this->assertInstanceOf('Spryker\Zed\Application\Business\Model\Navigation\NavigationBuilder', $instance);
    }

}
