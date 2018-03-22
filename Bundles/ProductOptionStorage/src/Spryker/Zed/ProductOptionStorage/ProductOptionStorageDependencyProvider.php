<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeBridge;
use Spryker\Zed\ProductOptionStorage\Dependency\QueryContainer\ProductOptionStorageToProductOptionQueryContainerBridge;
use Spryker\Zed\ProductOptionStorage\Dependency\QueryContainer\ProductOptionStorageToProductQueryContainerBridge;
use Spryker\Zed\ProductOptionStorage\Dependency\Service\ProductOptionStorageToUtilSanitizeServiceBridge;

class ProductOptionStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_PRODUCT_OPTION = 'QUERY_CONTAINER_PRODUCT_OPTION';
    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';
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
            return new ProductOptionStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return new ProductOptionStorageToProductOptionFacadeBridge($container->getLocator()->productOption()->facade());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductOptionStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::QUERY_CONTAINER_PRODUCT_OPTION] = function (Container $container) {
            return new ProductOptionStorageToProductOptionQueryContainerBridge($container->getLocator()->productOption()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductOptionStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }
}
