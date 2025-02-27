<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use SprykerTest\Client\Kernel\Fixtures\KernelFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
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
    public function testGetProvidedDependency(): void
    {
        // Assign
        $container = new Container([static::TEST_KEY => static::TEST_VALUE]);
        $factory = new KernelFactory();
        $factory->setContainer($container);

        // Act
        $dependency = $factory->getProvidedDependency(static::TEST_KEY);

        // Assert
        $this->assertSame(static::TEST_VALUE, $dependency);
    }

    /**
     * @return void
     */
    public function testGetProvidedDependencyWithLazyFetch(): void
    {
        // Assign
        $container = new Container([static::TEST_KEY => static::TEST_VALUE]);
        $factory = new KernelFactory();
        $factory->setContainer($container);

        // Act
        $wrappedDependency = $factory->getProvidedDependency(static::TEST_KEY, $factory::LOADING_LAZY);

        // Assert
        $this->assertSame(static::TEST_VALUE, $wrappedDependency());
    }
}
