<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer\ProductCategoryStorageToCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer\ProductCategoryStorageToProductCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer\ProductCategoryStorageToProductQueryContainerBridge;

class ProductCategoryStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const FACADE_CATEGORY = 'FACADE_CATEGORY';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductCategoryStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::FACADE_CATEGORY] = function (Container $container) {
            return new ProductCategoryStorageToCategoryBridge($container->getLocator()->category()->facade());
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
        $container[static::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return new ProductCategoryStorageToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new ProductCategoryStorageToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductCategoryStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }
}
