<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsStorage\Dependency\Facade\CmsStorageToCmsBridge;
use Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToCmsQueryContainerBridge;
use Spryker\Zed\CmsStorage\Dependency\QueryContainer\CmsStorageToLocaleQueryContainerBridge;
use Spryker\Zed\CmsStorage\Dependency\Service\CmsStorageToUtilSynchronizationBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsStorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_CMS_PAGE = 'QUERY_CONTAINER_CMS_PAGE';
    const QUERY_CONTAINER_LOCALE = 'QUERY_CONTAINER_LOCALE';
    const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';
    const FACADE_CMS = 'FACADE_CMS';
    const STORE = 'store';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SYNCHRONIZATION] = function (Container $container) {
            return new CmsStorageToUtilSynchronizationBridge($container->getLocator()->utilSynchronization()->service());
        };

        $container[static::FACADE_CMS] = function (Container $container) {
            return new CmsStorageToCmsBridge($container->getLocator()->cms()->facade());
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
        $container[static::QUERY_CONTAINER_CMS_PAGE] = function (Container $container) {
            return new CmsStorageToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_LOCALE] = function (Container $container) {
            return new CmsStorageToLocaleQueryContainerBridge($container->getLocator()->locale()->queryContainer());
        };

        return $container;
    }

}
