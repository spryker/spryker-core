<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Dependency\Injector;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Dependency
 * @group Injector
 * @group AbstractDependencyInjectorTest
 */
class AbstractDependencyInjectorTest extends \PHPUnit_Framework_TestCase
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
