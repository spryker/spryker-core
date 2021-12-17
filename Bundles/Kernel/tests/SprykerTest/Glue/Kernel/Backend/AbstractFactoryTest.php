<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Backend;

use Codeception\Test\Unit;
use ReflectionObject;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider as GlueAbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\Kernel\Backend\Exception\InvalidContainerException;
use Spryker\Glue\Kernel\Backend\Exception\InvalidDependencyProviderException;
use Spryker\Glue\Kernel\Container as GlueContainer;
use SprykerTest\Glue\Kernel\Fixtures\Backend\ConcreteFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group Backend
 * @group AbstractFactoryTest
 * Add your own group annotations below this line
 */
class AbstractFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testSetContainerReturnsFluentInterface(): void
    {
        $factory = new ConcreteFactory();
        $this->assertSame($factory, $factory->setContainer(new Container()));
    }

    /**
     * @return void
     */
    public function testGetContainerReturnsBackendContainer(): void
    {
        $factory = new ConcreteFactory();
        $this->assertInstanceOf(Container::class, $factory->getInternalContainer());
    }

    /**
     * @return void
     */
    public function testInvalidBundleDependencyProviderThrowsException(): void
    {
        $this->expectException(InvalidDependencyProviderException::class);
        $this->expectExceptionMessage(sprintf('Glue backend modules must use the %s', AbstractBundleDependencyProvider::class));

        $dependencyProviderMock = $this->createMock(GlueAbstractBundleDependencyProvider::class);
        $factory = new ConcreteFactory();
        $factoryReflection = new ReflectionObject($factory);
        $method = $factoryReflection->getMethod('provideDependencies');
        $method->setAccessible(true);
        $method->invokeArgs($factory, [$dependencyProviderMock, new Container()]);
    }

    /**
     * @return void
     */
    public function testInvalidContainerThrowsException(): void
    {
        $this->expectException(InvalidContainerException::class);
        $this->expectExceptionMessage(sprintf('Glue backend modules must use the %s', Container::class));

        $dependencyProviderMock = $this->createMock(AbstractBundleDependencyProvider::class);

        $factory = new ConcreteFactory();
        $factoryReflection = new ReflectionObject($factory);
        $method = $factoryReflection->getMethod('provideDependencies');
        $method->setAccessible(true);
        $method->invokeArgs($factory, [$dependencyProviderMock, new GlueContainer()]);
    }
}
