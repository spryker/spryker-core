<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Zed\Kernel\Communication\ControllerLocator;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Kernel
 * @group Communication
 * @group Locator
 * @group ControllerLocator
 */
class ControllerLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCanLocateShouldReturnFalseWhenControllerCanNotBeLocated()
    {
        $bundleControllerAction = new BundleControllerAction(new Request());
        $controllerLocator = new ControllerLocator($bundleControllerAction);

        $this->assertFalse($controllerLocator->canLocate());
    }

    public function testCanLocateShouldReturnTrueWhenControllerCanBeLocated()
    {
        $request = new Request([], [], ['module' => 'kernel', 'controller' => 'foo']);
        $bundleControllerAction = new BundleControllerAction($request);
        $controllerLocator = new ControllerLocator(
            $bundleControllerAction,
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\{{controller}}Controller'
        );

        $this->assertTrue($controllerLocator->canLocate());
    }

    public function testLocateShouldThrowExceptionIfControllerCanNotLocated()
    {
        $this->setExpectedException('\Exception');

        $bundleControllerAction = new BundleControllerAction(new Request());
        $controllerLocator = new ControllerLocator($bundleControllerAction);

        $controllerLocator->locate(new \Pimple(), Locator::getInstance());
    }

    public function testLocateShouldReturnClassWhenControllerCanBeLocated()
    {
        $request = new Request([], [], ['module' => 'kernel', 'controller' => 'foo']);
        $bundleControllerAction = new BundleControllerAction($request);
        $controllerLocator = new ControllerLocator(
            $bundleControllerAction,
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\{{controller}}Controller'
        );

        $locatedClass = $controllerLocator->locate(new \Pimple(), Locator::getInstance());
        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\FooController', $locatedClass);
    }

    public function testLocateShouldReturnClassWhenWidgetControllerCanBeLocated()
    {
        $request = new Request([], [], ['module' => 'kernel', 'controller' => 'foo']);
        $bundleControllerAction = new BundleControllerAction($request);
        $controllerLocator = new ControllerLocator(
            $bundleControllerAction,
            '',
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\{{controller}}Controller'
        );

        $locatedClass = $controllerLocator->locate(new \Pimple(), Locator::getInstance());
        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\FooController', $locatedClass);
    }

}
