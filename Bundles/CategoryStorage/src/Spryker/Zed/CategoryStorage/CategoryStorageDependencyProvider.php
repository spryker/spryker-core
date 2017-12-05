<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CategoryStorage\Dependency\QueryContainer\CategoryStorageToCategoryQueryContainerBridge;
use Spryker\Zed\CategoryStorage\Dependency\QueryContainer\CategoryStorageToLocaleQueryContainerBridge;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSynchronizationBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryStorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';
    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';
    const FACADE_CATEGORY = 'FACADE_CATEGORY';
    const STORE = 'store';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SYNCHRONIZATION] = function (Container $container) {
            return new CategoryStorageToUtilSynchronizationBridge($container->getLocator()->utilSynchronization()->service());
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
        $container[static::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new CategoryStorageToCategoryQueryContainerBridge($container->getLocator()->Category()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return new CategoryStorageToLocaleQueryContainerBridge($container->getLocator()->locale()->queryContainer());
        };

        return $container;
    }

}
