<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\ControllerResolver;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group ControllerResolver
 * @group ZedFragmentControllerResolverTest
 * Add your own group annotations below this line
 */
class ZedFragmentControllerResolverTest extends Unit
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
        $this->assertIsCallable($result);
    }

    /**
     * @return array
     */
    public function getController()
    {
        return [
            ['index/index/index', 'controller.service.index.index.index:indexAction'],
            ['/index/index/index', 'controller.service.index.index.index:indexAction'],
            ['Index/Index/Index', 'controller.service.index.index.index:indexAction'],
            ['/Index/Index/Index', 'controller.service.index.index.index:indexAction'],
            ['foo-bar/baz-bat/zip-zap', 'controller.service.fooBar.bazBat.zipZap:zipZapAction'],
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver
     */
    protected function getFragmentControllerProvider(Request $request)
    {
        $controllerResolverMock = $this->getMockBuilder(ZedFragmentControllerResolver::class)
            ->setMethods(['resolveController', 'getCurrentRequest'])
            ->disableOriginalConstructor()
            ->getMock();

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
