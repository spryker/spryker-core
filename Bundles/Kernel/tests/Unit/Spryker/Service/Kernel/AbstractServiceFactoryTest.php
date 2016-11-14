<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Unit\Spryker\Service\Kernel\Fixtures\ServiceFactory;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group AbstractFactoryTest
 */
class AbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{

    const CONTAINER_KEY = 'key';
    const CONTAINER_VALUE = 'value';

    /**
     * @return void
     */
    public function testSetContainer()
    {
        $container = new Container();
        $factory = new ServiceFactory();

        $this->assertSame($factory, $factory->setContainer($container));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsException()
    {
        $container = new Container();
        $factory = new ServiceFactory();

        $this->expectException(ContainerKeyNotFoundException::class);
        $factory->setContainer($container);
        $factory->getProvidedDependency('something');
    }

    /**
     * @return void
     */
    public function testGetProvidedDependency()
    {
        $container = new Container();
        $container[self::CONTAINER_KEY] = self::CONTAINER_VALUE;
        $factory = new ServiceFactory();

        $factory->setContainer($container);
        $this->assertSame(self::CONTAINER_VALUE, $factory->getProvidedDependency(self::CONTAINER_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyShouldResolveContainer()
    {
        $container = new Container();
        $container[self::CONTAINER_KEY] = self::CONTAINER_VALUE;

        $factoryMock = $this->getFactoryMock(['createContainerWithProvidedDependencies']);
        $factoryMock->expects($this->once())->method('createContainerWithProvidedDependencies')->willReturn($container);

        $this->assertSame(self::CONTAINER_VALUE, $factoryMock->getProvidedDependency(self::CONTAINER_KEY));
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\AbstractFactory
     */
    protected function getFactoryMock(array $methods)
    {
        $factoryMock = $this->getMockBuilder(ServiceFactory::class)->setMethods($methods)->getMock();

        return $factoryMock;
    }

}
