<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector;

abstract class AbstractBusinessFactory extends AbstractFactory implements BusinessFactoryInterface
{
    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->provideBusinessLayerDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectExternalDependencies(
        DependencyInjector $dependencyInjector,
        Container $container
    ) {
        return $dependencyInjector->injectBusinessLayerDependencies($container);
    }
}
