<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Facade\CmsBlockProductStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\CmsBlockProductStorage\Dependency\QueryContainer\CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerBridge;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsBlockProductStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR = 'QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new CmsBlockProductStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new CmsBlockProductStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR] = function (Container $container) {
            return new CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerBridge($container->getLocator()->cmsBlockProductConnector()->queryContainer());
        };

        return $container;
    }
}
