<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\Synchronization\Dependency\Service\SynchronizationToUtilSynchronizationServiceBridge;

class SynchronizationDependencyProvider extends AbstractBundleDependencyProvider
{

    const SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS = 'SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS';
    const SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS = 'SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS';
    const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return void
     */
    public function provideServiceDependencies(Container $container)
    {
        $container[self::SYNCHRONIZATION_STORAGE_KEY_GENERATOR_PLUGINS] = function (Container $container) {
            return $this->getStorageKeyGeneratorPlugins($container);
        };

        $container[self::SYNCHRONIZATION_SEARCH_KEY_GENERATOR_PLUGINS] = function (Container $container) {
            return $this->getSearchKeyGeneratorPlugins($container);
        };

        $container[self::SERVICE_UTIL_SYNCHRONIZATION] = function (Container $container) {
            return new SynchronizationToUtilSynchronizationServiceBridge($container->getLocator()->utilSynchronization()->service());
        };
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

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface[]
     */
    protected function getSearchKeyGeneratorPlugins(Container $container)
    {
        return [];
    }

}
