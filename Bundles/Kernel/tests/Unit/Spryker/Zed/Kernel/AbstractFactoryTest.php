<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollection;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Unit\Spryker\Zed\Kernel\Fixtures\Factory;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group AbstractFactory
 */
class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    const CONTAINER_KEY = 'key';
    const CONTAINER_VALUE = 'value';

    /**
     * @return void
     */
    public function testSetContainer()
    {
        $container = new Container();
        $factory = new Factory();

        $this->assertSame($factory, $factory->setContainer($container));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsException()
    {
        $container = new Container();
        $factory = new Factory();

        $this->setExpectedException(ContainerKeyNotFoundException::class);
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
        $factory = new Factory();

        $factory->setContainer($container);
        $this->assertSame(self::CONTAINER_VALUE, $factory->getProvidedDependency(self::CONTAINER_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyProvideExternalDependencies()
    {
        $factory = new Factory();

        $this->assertSame(self::CONTAINER_VALUE, $factory->getProvidedDependency(self::CONTAINER_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyShouldResolveContainer()
    {
        $container = new Container();
        $container[self::CONTAINER_KEY] = self::CONTAINER_VALUE;

        $factoryMock = $this->getFactoryMock(['getContainerWithProvidedDependencies']);
        $factoryMock->expects($this->once())->method('getContainerWithProvidedDependencies')->willReturn($container);

        $this->assertSame(self::CONTAINER_VALUE, $factoryMock->getProvidedDependency(self::CONTAINER_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyShouldGetInjectedData()
    {
        $dependencyInjectorResolver = $this->getDependencyInjectorResolverMock();
        $factoryMock = $this->getFactoryMock(['getDependencyInjectorResolver', 'resolveDependencyProvider']);
        $factoryMock->expects($this->once())->method('getDependencyInjectorResolver')->willReturn($dependencyInjectorResolver);

        $abstractBundleDependencyProviderMock = $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
        $factoryMock->expects($this->once())->method('resolveDependencyProvider')->willReturn($abstractBundleDependencyProviderMock);

        $this->assertSame(self::CONTAINER_VALUE, $factoryMock->getProvidedDependency(self::CONTAINER_KEY));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function getDependencyInjectorResolverMock()
    {
        $container = new Container();
        $container[self::CONTAINER_KEY] = self::CONTAINER_VALUE;

        $dependencyInjectorMock = $this->getMock(DependencyInjectorInterface::class, ['inject']);
        $dependencyInjectorMock->expects($this->once())->method('inject')->willReturn($container);

        $dependencyInjectorCollectionMock = $this->getMock(DependencyInjectorCollection::class, ['getDependencyInjector']);
        $dependencyInjectorCollectionMock->expects($this->once())->method('getDependencyInjector')->willReturn(
            [$dependencyInjectorMock]
        );
        $dependencyInjectorResolverMock = $this->getMock(DependencyInjectorResolver::class, ['resolve']);
        $dependencyInjectorResolverMock->expects($this->once())->method('resolve')->willReturn($dependencyInjectorCollectionMock);

        return $dependencyInjectorResolverMock;
    }

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\AbstractFactory
     */
    protected function getFactoryMock(array $methods)
    {
        $methods[] = 'provideExternalDependencies';
        $factoryMock = $this->getMock(AbstractFactory::class, $methods);

        return $factoryMock;
    }

}
