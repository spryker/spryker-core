<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch;

use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductReviewSearch\Dependency\Facade\ProductReviewSearchToProductPageSearchFacadeBridge;
use Spryker\Zed\ProductReviewSearch\Dependency\QueryContainer\ProductReviewSearchToProductReviewQueryContainerBridge;
use Spryker\Zed\ProductReviewSearch\Dependency\Service\ProductReviewSearchToUtilEncodingBridge;

class ProductReviewSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_PRODUCT_REVIEW = 'QUERY_CONTAINER_PRODUCT_REVIEW';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const STORE = 'STORE';
    public const PROPEL_QUERY_PRODUCT_REVIEW = 'PROPEL_QUERY_PRODUCT_REVIEW';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductReviewSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        $container[static::FACADE_PRODUCT_PAGE_SEARCH] = function (Container $container) {
            return new ProductReviewSearchToProductPageSearchFacadeBridge($container->getLocator()->productPageSearch()->facade());
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

        $container = $this->addPropelProductReviewQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductReviewQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_REVIEW] = function (): SpyProductReviewQuery {
            return SpyProductReviewQuery::create();
        };

        return $container;
    }
}
