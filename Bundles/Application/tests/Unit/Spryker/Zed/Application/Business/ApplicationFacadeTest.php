<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Application\Business\Model;

use Spryker\Zed\Application\Business\ApplicationFacade;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group ApplicationFacade
 */
class ApplicationFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Application\Business\ApplicationFacade
     */
    private function getFacade()
    {
        return new ApplicationFacade();
    }

    /**
     * @return void
     */
    public function testBuildNavigationShouldReturnArrayWithMenuAsKey()
    {
        $navigation = $this->getFacade()->buildNavigation('');

        $this->assertArrayHasKey('menu', $navigation);
    }

}
