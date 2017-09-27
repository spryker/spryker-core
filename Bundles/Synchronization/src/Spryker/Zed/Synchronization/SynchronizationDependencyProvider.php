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
    const PLUGIN_SEARCH_DATA_MAP = 'PLUGIN_SEARCH_DATA_MAP';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new SynchronizationToStorageBridge($container->getLocator()->storage()->client());
        };

        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return new SynchronizationToSearchBridge($container->getLocator()->search()->client());
        };

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
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new SynchronizationToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

}
