<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductReviewGui\Dependency\Facade\ProductReviewGuiToLocaleBridge;
use Spryker\Zed\ProductReviewGui\Dependency\Facade\ProductReviewGuiToProductReviewBridge;
use Spryker\Zed\ProductReviewGui\Dependency\QueryContainer\ProductReviewGuiToProductReviewBridge as ProductReviewGuiToProductReviewQueryContainerBridge;
use Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilDateTimeBridge as ServiceProductReviewGuiToDateTimeBridge;
use Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilSanitizeBridge as ServiceProductReviewGuiToUtilSanitizeBridge;

class ProductReviewGuiDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT_REVIEW = 'FACADE_PRODUCT_REVIEW';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    const QUERY_CONTAINER_PRODUCT_REVIEW = 'QUERY_CONTAINER_PRODUCT_REVIEW';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideProductReviewFacade($container);
        $this->provideLocaleFacade($container);
        $this->provideUtilSanitizeService($container);
        $this->provideUtilDateTimeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUtilSanitizeService(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new ServiceProductReviewGuiToUtilSanitizeBridge($container->getLocator()->utilSanitize()->service());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUtilDateTimeService(Container $container)
    {
        $container[static::SERVICE_UTIL_DATE_TIME] = function (Container $container) {
            return new ServiceProductReviewGuiToDateTimeBridge($container->getLocator()->utilDateTime()->service());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->provideProductReviewQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductReviewFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_REVIEW] = function (Container $container) {
            return new ProductReviewGuiToProductReviewBridge($container->getLocator()->productReview()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductReviewQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_REVIEW] = function (Container $container) {
            return new ProductReviewGuiToProductReviewQueryContainerBridge($container->getLocator()->productReview()->queryContainer());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductReviewGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

}
