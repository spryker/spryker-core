<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductGroupStorage\Dependency\Facade\ProductGroupStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductGroupStorage\Dependency\QueryContainer\ProductGroupStorageToProductGroupQueryContainerBridge;
use Spryker\Zed\ProductGroupStorage\Dependency\QueryContainer\ProductGroupStorageToProductQueryContainerBridge;

class ProductGroupStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_PRODUCT_GROUP = 'QUERY_CONTAINER_PRODUCT_GROUP';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductGroupStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::QUERY_CONTAINER_PRODUCT_GROUP] = function (Container $container) {
            return new ProductGroupStorageToProductGroupQueryContainerBridge($container->getLocator()->productGroup()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductGroupStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }
}
