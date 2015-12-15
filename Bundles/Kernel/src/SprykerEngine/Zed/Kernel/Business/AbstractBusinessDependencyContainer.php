<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use Spryker\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Kernel\Container;

abstract class AbstractBusinessDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
{

    /**
     * @param AbstractBundleDependencyProvider $dependencyProvider
     * @param Container $container
     *
     * @return Container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ) {
        $dependencyProvider->provideBusinessLayerDependencies($container);
    }

}
