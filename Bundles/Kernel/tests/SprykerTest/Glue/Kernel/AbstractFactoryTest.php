<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerTest\Glue\Kernel\Fixtures\ConcreteFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group AbstractFactoryTest
 * Add your own group annotations below this line
 */
class AbstractFactoryTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_KEY = 'test';

    /**
     * @var string
     */
    public const TEST_VALUE = 'value';

    /**
     * @return void
     */
    public function testSetContainer(): void
    {
        $factory = new ConcreteFactory();
        $this->assertSame($factory, $factory->setContainer(new Container()));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependency(): void
    {
        $container = new Container([static::TEST_KEY => static::TEST_VALUE]);

        $factory = new ConcreteFactory();
        $factory->setContainer($container);
        $this->assertSame(static::TEST_VALUE, $factory->getProvidedDependency(static::TEST_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyWithResolvedProvider(): void
    {
        $factoryMock = $this->getFactoryMock();

        $this->assertSame(static::TEST_VALUE, $factoryMock->getProvidedDependency(static::TEST_KEY));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Glue\Kernel\Fixtures\ConcreteFactory
     */
    protected function getFactoryMock(): ConcreteFactory
    {
        $dependencyResolverMock = $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
        $container = new Container([static::TEST_KEY => static::TEST_VALUE]);

        $factoryMock = $this->getMockForAbstractClass(ConcreteFactory::class, [], '', true, true, true, ['resolveDependencyProvider', 'createContainer']);
        $factoryMock->expects($this->once())->method('resolveDependencyProvider')->willReturn($dependencyResolverMock);
        $factoryMock->expects($this->once())->method('createContainer')->willReturn($container);

        return $factoryMock;
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsExceptionWhenProviderNotFound(): void
    {
        $factory = new ConcreteFactory();
        $this->expectException(ContainerKeyNotFoundException::class);

        $factory->getProvidedDependency(static::TEST_KEY);
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsExceptionWhenKeyNotInContainer(): void
    {
        $container = new Container();
        $factory = new ConcreteFactory();
        $factory->setContainer($container);
        $this->expectException(ContainerKeyNotFoundException::class);

        $factory->getProvidedDependency(static::TEST_KEY);
    }
}
