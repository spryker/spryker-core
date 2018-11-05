<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientBridge;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientBridge;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageClientBridge;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceBridge;

class SynchronizationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const CLIENT_QUEUE = 'CLIENT_QUEUE';
    public const SERVICE_UTIL_ENCODING = 'UTIL_ENCODING_SERVICE';
    public const PLUGINS_SYNCHRONIZATION_DATA = 'PLUGINS_SYNCHRONIZATION_DATA';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSearchClient($container);
        $container = $this->addQueueClient($container);
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
            return new SynchronizationToStorageClientBridge($container->getLocator()->storage()->client());
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
            return new SynchronizationToSearchClientBridge($container->getLocator()->search()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueueClient(Container $container)
    {
        $container[self::CLIENT_QUEUE] = function (Container $container) {
            return new SynchronizationToQueueClientBridge($container->getLocator()->queue()->client());
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
            return new SynchronizationToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
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
        $container[self::PLUGINS_SYNCHRONIZATION_DATA] = function (Container $container) {
            return $this->getSynchronizationDataPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[]
     */
    protected function getSynchronizationDataPlugins()
    {
        return [];
    }
}
