<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractDependencyContainer as BaseDependencyContainer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\DependencyContainer\DependencyContainerInterface;

abstract class AbstractPersistenceDependencyContainer extends BaseDependencyContainer implements DependencyContainerInterface
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
        $dependencyProvider->providePersistenceLayerDependencies($container);
    }

}
