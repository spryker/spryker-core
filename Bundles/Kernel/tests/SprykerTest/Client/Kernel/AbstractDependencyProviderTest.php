<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Kernel
 * @group AbstractDependencyProviderTest
 * Add your own group annotations below this line
 */
class AbstractDependencyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testCallProvideServiceLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideServiceLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Kernel\AbstractDependencyProvider
     */
    private function getAbstractDependencyProviderMock()
    {
        return $this->getMockForAbstractClass(AbstractDependencyProvider::class);
    }

    /**
     * @return void
     */
    public function testProvideServiceLayerDependencies()
    {
        $container = new Container();
        $abstractDependencyContainerMock = $this->getAbstractDependencyContainerMock();
        $this->assertInstanceOf(Container::class, $abstractDependencyContainerMock->provideServiceLayerDependencies($container));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Kernel\AbstractDependencyProvider
     */
    private function getAbstractDependencyContainerMock()
    {
        $abstractDependencyContainerMock = $this->getMockForAbstractClass(AbstractDependencyProvider::class);

        return $abstractDependencyContainerMock;
    }
}
