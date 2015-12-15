<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\Spryker\Yves\Kernel;

use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ControllerLocator;
use Spryker\Yves\Kernel\Locator;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group Locator
 * @group ControllerLocator
 */
class ControllerLocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCanLocateShouldReturnFalseWhenControllerCanNotBeLocated()
    {
        $bundleControllerAction = new BundleControllerAction('Kernel', 'Foo', 'index');
        $locator = new ControllerLocator($bundleControllerAction);

        $this->assertFalse($locator->canLocate());
    }

    /**
     * @return void
     */
    public function testLocateShouldThrowExceptionWhenControllerCanNotBeLocated()
    {
        $this->setExpectedException('\Exception');

        $bundleControllerAction = new BundleControllerAction('Kernel', 'Foo', 'index');
        $locator = new ControllerLocator($bundleControllerAction);

        $locator->locate(new \Pimple(), Locator::getInstance());
    }

}
