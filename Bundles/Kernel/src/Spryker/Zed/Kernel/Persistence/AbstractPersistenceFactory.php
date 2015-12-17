<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\PersistenceFactoryInterface;

abstract class AbstractPersistenceFactory extends AbstractFactory implements PersistenceFactoryInterface
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
