<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\Communication\BundleControllerAction;
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

    public function testGetControllerShouldReturnControllerNameFromRequest()
    {
        $request = $this->getRequestTestObject();
        $bundleControllerAction = new BundleControllerAction($request);

        $this->assertSame(ucfirst(self::CONTROLLER), $bundleControllerAction->getController());
    }

    public function testGetActionShouldReturnActionNameFromRequest()
    {
        $request = $this->getRequestTestObject();
        $bundleControllerAction = new BundleControllerAction($request);

        $this->assertSame(ucfirst(self::ACTION), $bundleControllerAction->getAction());
    }

}
