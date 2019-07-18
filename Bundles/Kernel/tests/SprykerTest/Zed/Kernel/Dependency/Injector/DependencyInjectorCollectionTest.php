<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Dependency\Injector;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollection;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Dependency
 * @group Injector
 * @group DependencyInjectorCollectionTest
 * Add your own group annotations below this line
 */
class DependencyInjectorCollectionTest extends Unit
{
    /**
     * @return void
     */
    public function testAddDependencyInjectorShouldReturnInstance()
    {
        $dependencyInjectorCollection = new DependencyInjectorCollection();
        $result = $dependencyInjectorCollection->addDependencyInjector($this->getDependencyInjectorMock());

        $this->assertInstanceOf(DependencyInjectorCollectionInterface::class, $result);
    }

    /**
     * @return void
     */
    public function testGetDependencyInjectorShouldReturnInstance()
    {
        $dependencyInjectorCollection = new DependencyInjectorCollection();
        $dependencyInjectorMock = $this->getDependencyInjectorMock();
        $dependencyInjectorCollection->addDependencyInjector($dependencyInjectorMock);

        $dependencyInjector = $dependencyInjectorCollection->getDependencyInjector();
        $this->assertSame($dependencyInjectorMock, $dependencyInjector[0]);
    }

    /**
     * @return void
     */
    public function testCountShouldReturnCountOfAddedDependencyInjector()
    {
        $dependencyInjectorCollection = new DependencyInjectorCollection();
        $this->assertSame(0, $dependencyInjectorCollection->count());

        $dependencyInjectorMock = $this->getDependencyInjectorMock();
        $dependencyInjectorCollection->addDependencyInjector($dependencyInjectorMock);

        $this->assertSame(1, $dependencyInjectorCollection->count());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Dependency\Injector\DependencyInjectorInterface
     */
    private function getDependencyInjectorMock()
    {
        return $this->getMockBuilder(DependencyInjectorInterface::class)->getMock();
    }
}
