<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Kernel
 * @group RouteNameResolver
 */
class BundleControllerActionTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE = 'foo';
    const CONTROLLER = 'bar';
    const ACTION = 'baz';

    /**
     * @return void
     */
    public function testGetBundleShouldReturnBundleNameFromRequest()
    {
        $request = $this->getRequestTestObject();
        $bundleControllerAction = new BundleControllerAction($request);

        $this->assertSame(ucfirst(self::BUNDLE), $bundleControllerAction->getBundle());
    }

    /**
     * @return Request
     */
    private function getRequestTestObject()
    {
        $request = new Request(
            [],
            [],
            ['module' => self::BUNDLE, 'controller' => self::CONTROLLER, 'action' => self::ACTION]
        );

        return $request;
    }

    /**
     * @return void
     */
    public function testGetControllerShouldReturnControllerNameFromRequest()
    {
        $request = $this->getRequestTestObject();
        $bundleControllerAction = new BundleControllerAction($request);

        $this->assertSame(ucfirst(self::CONTROLLER), $bundleControllerAction->getController());
    }

    /**
     * @return void
     */
    public function testGetActionShouldReturnActionNameFromRequest()
    {
        $request = $this->getRequestTestObject();
        $bundleControllerAction = new BundleControllerAction($request);

        $this->assertSame(ucfirst(self::ACTION), $bundleControllerAction->getAction());
    }

}
