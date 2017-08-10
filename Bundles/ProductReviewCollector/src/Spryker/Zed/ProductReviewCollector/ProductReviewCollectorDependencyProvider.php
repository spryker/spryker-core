<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToCollectorBridge;
use Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToProductReviewBridge;
use Spryker\Zed\ProductReviewCollector\Dependency\Facade\ProductReviewCollectorToSearchBridge;

class ProductReviewCollectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    const FACADE_PRODUCT_REVIEW = 'QUERY_CONTAINER_PRODUCT_REVIEW';
    const FACADE_SEARCH = 'FACADE_SEARCH';

    const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addCollectorFacade($container);
        $this->addSearchFacade($container);
        $this->addProductReviewFacade($container);
        $this->addDataReaderService($container);
        $this->addTouchQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new ProductReviewCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSearchFacade(Container $container)
    {
        $container[static::FACADE_SEARCH] = function (Container $container) {
            return new ProductReviewCollectorToSearchBridge($container->getLocator()->search()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductReviewFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_REVIEW] = function (Container $container) {
            return new ProductReviewCollectorToProductReviewBridge($container->getLocator()->productReview()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };
    }

}
