<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
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
        $factory = new Factory('Application');

        return new ApplicationFacade($factory, $this->getLocator());
    }

    /**
     * @return Locator|AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    public function testBuildNavigationShouldReturnArrayWithMenuAsKey()
    {
        $navigation = $this->getFacade()->buildNavigation('');

        $this->assertArrayHasKey('menu', $navigation);
    }

}
