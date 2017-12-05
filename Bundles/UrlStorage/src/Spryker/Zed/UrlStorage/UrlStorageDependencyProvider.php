<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\UrlStorage\Dependency\Facade\UrlStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\UrlStorage\Dependency\QueryContainer\UrlStorageToUrlQueryContainerBridge;
use Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceBridge;

class UrlStorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_URL = 'QUERY_CONTAINER_URL';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new UrlStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new UrlStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };
        $container[static::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_URL] = function (Container $container) {
            return new UrlStorageToUrlQueryContainerBridge($container->getLocator()->url()->queryContainer());
        };

        return $container;
    }

}
