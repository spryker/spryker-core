<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel;

use Codeception\Test\Unit;
use Spryker\Service\Kernel\Container;
use Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerTest\Service\Kernel\Fixtures\ServiceFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Kernel
 * @group AbstractServiceFactoryTest
 * Add your own group annotations below this line
 */
class AbstractServiceFactoryTest extends Unit
{
    /**
     * @var string
     */
    public const CONTAINER_KEY = 'key';

    /**
     * @var string
     */
    public const CONTAINER_VALUE = 'value';

    /**
     * @return void
     */
    public function testSetContainer(): void
    {
        $container = new Container();
        $factory = new ServiceFactory();

        $this->assertSame($factory, $factory->setContainer($container));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsException(): void
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
    public function testGetProvidedDependency(): void
    {
        // Assign
        $container = new Container();
        $container->set(static::CONTAINER_KEY, static::CONTAINER_VALUE);
        $factory = new ServiceFactory();
        $factory->setContainer($container);

        // Act
        $dependency = $factory->getProvidedDependency(static::CONTAINER_KEY);

        // Assert
        $this->assertSame(static::CONTAINER_VALUE, $dependency);
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyWithLazyFetch(): void
    {
        // Assign
        $container = new Container();
        $container->set(static::CONTAINER_KEY, static::CONTAINER_VALUE);
        $factory = new ServiceFactory();
        $factory->setContainer($container);

        // Act
        $wrappedDependency = $factory->getProvidedDependency(static::CONTAINER_KEY, $factory::LOADING_LAZY);

        // Assert
        $this->assertSame(static::CONTAINER_VALUE, $wrappedDependency());
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyShouldResolveContainer(): void
    {
        $container = new Container();
        $container->set(static::CONTAINER_KEY, static::CONTAINER_VALUE);

        $factoryMock = $this->getFactoryMock(['createContainerWithProvidedDependencies']);
        $factoryMock->expects($this->once())->method('createContainerWithProvidedDependencies')->willReturn($container);

        $this->assertSame(static::CONTAINER_VALUE, $factoryMock->getProvidedDependency(static::CONTAINER_KEY));
    }

    /**
     * @param array $methods
     *
     * @return \SprykerTest\Service\Kernel\Fixtures\ServiceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFactoryMock(array $methods): ServiceFactory
    {
        $factoryMock = $this->getMockBuilder(ServiceFactory::class)->onlyMethods($methods)->getMock();

        return $factoryMock;
    }
}
