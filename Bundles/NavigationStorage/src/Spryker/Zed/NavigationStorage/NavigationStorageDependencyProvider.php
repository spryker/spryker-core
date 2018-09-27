<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\NavigationStorage\Dependency\Facade\NavigationStorageToNavigationBridge;
use Spryker\Zed\NavigationStorage\Dependency\QueryContainer\NavigationStorageToLocaleQueryContainerBridge;
use Spryker\Zed\NavigationStorage\Dependency\QueryContainer\NavigationStorageToNavigationQueryContainerBridge;
use Spryker\Zed\NavigationStorage\Dependency\Service\NavigationStorageToUtilSanitizeServiceBridge;

class NavigationStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_NAVIGATION = 'QUERY_CONTAINER_NAVIGATION';
    public const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';
    public const FACADE_NAVIGATION = 'FACADE_NAVIGATION';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const STORE = 'store';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new NavigationStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new NavigationStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_NAVIGATION] = function (Container $container) {
            return new NavigationStorageToNavigationBridge($container->getLocator()->navigation()->facade());
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
        $container[static::QUERY_CONTAINER_NAVIGATION] = function (Container $container) {
            return new NavigationStorageToNavigationQueryContainerBridge($container->getLocator()->navigation()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return new NavigationStorageToLocaleQueryContainerBridge($container->getLocator()->locale()->queryContainer());
        };

        return $container;
    }
}
