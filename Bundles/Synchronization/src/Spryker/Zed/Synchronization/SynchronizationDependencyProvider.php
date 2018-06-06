<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchBridge;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageBridge;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingBridge;

class SynchronizationDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_STORAGE = 'CLIENT_STORAGE';
    const CLIENT_SEARCH = 'CLIENT_SEARCH';
    const SERVICE_UTIL_ENCODING = 'UTIL_ENCODING_SERVICE';
    const SYNCHRONIZATION_DATA_PLUGINS = 'SYNCHRONIZATION_DATA_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSearchClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addSynchronizationDataPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new SynchronizationToStorageBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return new SynchronizationToSearchBridge($container->getLocator()->search()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new SynchronizationToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSynchronizationDataPlugins($container)
    {
        $container[self::SYNCHRONIZATION_DATA_PLUGINS] = function (Container $container) {
            return $this->getSynchronizationDataPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]
     */
    protected function getSynchronizationDataPlugins()
    {
        return [];
    }
}
