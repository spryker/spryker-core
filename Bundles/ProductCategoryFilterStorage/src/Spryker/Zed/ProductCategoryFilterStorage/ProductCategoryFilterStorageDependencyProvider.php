<?php

namespace Spryker\Zed\ProductCategoryFilterStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\Facade\ProductCategoryFilterStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\QueryContainer\ProductCategoryFilterStorageToProductCategoryFilterQueryContainerBridge;
use Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilSanitizeServiceBridge;

class ProductCategoryFilterStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const QUERY_CONTAINER_PRODUCT_CATEGORY_FILTER = 'QUERY_CONTAINER_PRODUCT_CATEGORY_FILTER';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new ProductCategoryFilterStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductCategoryFilterStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::QUERY_CONTAINER_PRODUCT_CATEGORY_FILTER] = function (Container $container) {
            return new ProductCategoryFilterStorageToProductCategoryFilterQueryContainerBridge($container->getLocator()->productCategoryFilter()->queryContainer());
        };

        return $container;
    }
}
