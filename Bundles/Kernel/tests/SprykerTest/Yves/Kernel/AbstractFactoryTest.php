<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;
use SprykerTest\Yves\Kernel\Fixtures\ConcreteFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group AbstractFactoryTest
 * Add your own group annotations below this line
 */
class AbstractFactoryTest extends Unit
{
    public const TEST_KEY = 'test';
    public const TEST_VALUE = 'value';

    /**
     * @return void
     */
    public function testSetContainer()
    {
        $factory = new ConcreteFactory();
        $this->assertSame($factory, $factory->setContainer(new Container()));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependency()
    {
        $container = new Container([self::TEST_KEY => self::TEST_VALUE]);

        $factory = new ConcreteFactory();
        $factory->setContainer($container);
        $this->assertSame(self::TEST_VALUE, $factory->getProvidedDependency(self::TEST_KEY));
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyWithResolvedProvider()
    {
        $factoryMock = $this->getFactoryMock();

        $this->assertSame(self::TEST_VALUE, $factoryMock->getProvidedDependency(self::TEST_KEY));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Yves\Kernel\Fixtures\ConcreteFactory
     */
    protected function getFactoryMock()
    {
        $dependencyResolverMock = $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
        $container = new Container([self::TEST_KEY => self::TEST_VALUE]);

        $factoryMock = $this->getMockForAbstractClass(ConcreteFactory::class, [], '', true, true, true, ['resolveDependencyProvider', 'createContainer']);
        $factoryMock->expects($this->once())->method('resolveDependencyProvider')->willReturn($dependencyResolverMock);
        $factoryMock->expects($this->once())->method('createContainer')->willReturn($container);

        return $factoryMock;
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsExceptionWhenProviderNotFound()
    {
        $factory = new ConcreteFactory();
        $this->expectException(DependencyProviderNotFoundException::class);

        $factory->getProvidedDependency(self::TEST_KEY);
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyThrowsExceptionWhenKeyNotInContainer()
    {
        $container = new Container();
        $factory = new ConcreteFactory();
        $factory->setContainer($container);
        $this->expectException(ContainerKeyNotFoundException::class);

        $factory->getProvidedDependency(self::TEST_KEY);
    }
}
