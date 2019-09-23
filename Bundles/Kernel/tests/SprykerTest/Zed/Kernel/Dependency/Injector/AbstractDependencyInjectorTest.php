<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Dependency\Injector;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Dependency
 * @group Injector
 * @group AbstractDependencyInjectorTest
 * Add your own group annotations below this line
 */
class AbstractDependencyInjectorTest extends Unit
{
    /**
     * @return void
     */
    public function testInjectBusinessLayerDependenciesShouldReturnContainer()
    {
        $dependencyInjector = new AbstractDependencyInjector();
        $container = new Container();
        $container = $dependencyInjector->injectBusinessLayerDependencies($container);

        $this->assertInstanceOf(Container::class, $container);
    }

    /**
     * @return void
     */
    public function testInjectCommunicationLayerDependenciesShouldReturnContainer()
    {
        $dependencyInjector = new AbstractDependencyInjector();
        $container = new Container();
        $container = $dependencyInjector->injectCommunicationLayerDependencies($container);

        $this->assertInstanceOf(Container::class, $container);
    }

    /**
     * @return void
     */
    public function testInjectPersistenceLayerDependenciesShouldReturnContainer()
    {
        $dependencyInjector = new AbstractDependencyInjector();
        $container = new Container();
        $container = $dependencyInjector->injectPersistenceLayerDependencies($container);

        $this->assertInstanceOf(Container::class, $container);
    }
}
