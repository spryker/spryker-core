<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Application\Business\Model;

use SprykerFeature\Zed\Application\Business\ApplicationFacade;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Application
 * @group Business
 * @group ApplicationFacade
 */
class ApplicationFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ApplicationFacade
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
