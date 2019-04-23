<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class SynchronizationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS = 'SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS';
    public const SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS = 'SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addSynchronizationStorageKeyPlugins($container);
        $this->addSynchronizationSearchKeyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addSynchronizationSearchKeyPlugins(Container $container)
    {
        $container[self::SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS] = function (Container $container) {
            return $this->getSearchKeyGeneratorPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addSynchronizationStorageKeyPlugins(Container $container)
    {
        $container[self::SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS] = function (Container $container) {
            return $this->getStorageKeyGeneratorPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected function getSearchKeyGeneratorPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected function getStorageKeyGeneratorPlugins(Container $container)
    {
        return [];
    }
}
