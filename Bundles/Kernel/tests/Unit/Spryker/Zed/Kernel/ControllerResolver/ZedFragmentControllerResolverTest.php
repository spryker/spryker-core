<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ControllerResolver;

use Silex\Application;
use Spryker\Zed\Kernel\ControllerResolver\ZedFragmentControllerResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Kernel
 * @group Zed
 * @group ControllerResolver
 */
class ZedFragmentControllerResolverTest extends \PHPUnit_Framework_TestCase
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
            ['index/index/index', 'controller.service.index.index.index:indexAction'],
            ['/index/index/index', 'controller.service.index.index.index:indexAction'],
            ['Index/Index/Index', 'controller.service.index.index.index:indexAction'],
            ['/Index/Index/Index', 'controller.service.index.index.index:indexAction'],
            ['foo-bar/baz-bat/zip-zap', 'controller.service.fooBar.bazBat.zipZap:zipZapAction'],
        ];
    }

    /**
     * @param $name
     * @param array $arguments
     *
     * @return void
     */
    public function __call($name, $arguments = [])
    {
    }

    /**
     * @param Request $request
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ZedFragmentControllerResolver
     */
    protected function getFragmentControllerProvider(Request $request)
    {
        $controllerResolverMock = $this->getMock(ZedFragmentControllerResolver::class, ['resolveController', 'getCurrentRequest'], [], '', false);
        $controllerResolverMock->method('resolveController')->willReturn($this);
        $controllerResolverMock->method('getCurrentRequest')->willReturn($request);

        return $controllerResolverMock;
    }

    /**
     * @param string $controller
     *
     * @return Request
     */
    private function getRequest($controller)
    {
        return new Request([], [], ['_controller' => $controller]);
    }

}
