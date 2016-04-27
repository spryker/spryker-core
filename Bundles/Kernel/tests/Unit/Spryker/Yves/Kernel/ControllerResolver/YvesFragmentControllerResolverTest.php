<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\ControllerResolver;

use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Kernel
 * @group Yves
 * @group ControllerResolver
 */
class YvesFragmentControllerResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getController
     *
     * @param string $controller
     * @param string $expectedServiceName
     *
     * @return void
     */
    public function testCreateController($controller, $expectedServiceName)
    {
        $request = $this->getRequest($controller);
        $controllerResolver = $this->getFragmentControllerProvider($request);

        $result = $controllerResolver->getController($request);

        $this->assertSame($expectedServiceName, $request->attributes->get('_controller'));
        $this->assertInternalType('callable', $result);
    }

    /**
     * @return array
     */
    public function getController()
    {
        return [
            ['index/index/index', 'Unit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['/index/index/index', 'Unit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['Index/Index/Index', 'Unit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['/Index/Index/Index', 'Unit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::indexAction'],
            ['foo-bar/baz-bat/zip-zap', 'Unit\Spryker\Zed\Kernel\ControllerResolver\YvesFragmentControllerResolverTest::zipZapAction'],
        ];
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return void
     */
    public function __call($name, $arguments = [])
    {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver
     */
    protected function getFragmentControllerProvider(Request $request)
    {
        $controllerResolverMock = $this->getMock(YvesFragmentControllerResolver::class, ['resolveController', 'getCurrentRequest'], [], '', false);
        $controllerResolverMock->method('resolveController')->willReturn($this);
        $controllerResolverMock->method('getCurrentRequest')->willReturn($request);

        return $controllerResolverMock;
    }

    /**
     * @param string $controller
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest($controller)
    {
        return new Request([], [], ['_controller' => $controller]);
    }

}
