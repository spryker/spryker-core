<?php

namespace Spryker\Zed\ProductReviewSearch;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductReviewSearch\Dependency\QueryContainer\ProductReviewSearchToProductReviewQueryContainerBridge;
use Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingBridge;
use Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilSanitizeServiceBridge;

class ProductReviewSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_PRODUCT_REVIEW = 'QUERY_CONTAINER_PRODUCT_REVIEW';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new ProductReviewSearchToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductReviewSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };
        
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductReviewSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
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
        $container[static::QUERY_CONTAINER_PRODUCT_REVIEW] = function (Container $container) {
            return new ProductReviewSearchToProductReviewQueryContainerBridge($container->getLocator()->productReview()->queryContainer());
        };

        return $container;
    }
}
