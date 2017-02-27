<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DataFeedDependencyProvider extends AbstractBundleDependencyProvider
{

    const PRODUCT_QUERY_CONTAINER = 'PRODUCT_QUERY_CONTAINER';
    const CATEGORY_QUERY_CONTAINER = 'CATEGORY_QUERY_CONTAINER';
    const PRICE_QUERY_CONTAINER = 'PRICE_QUERY_CONTAINER';
    const STOCK_QUERY_CONTAINER = 'STOCK_QUERY_CONTAINER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::PRODUCT_QUERY_CONTAINER] = function (Container $container) {
            //todo: add bridge.
            return $container->getLocator()->product()->queryContainer();
        };

        $container[self::CATEGORY_QUERY_CONTAINER] = function (Container $container) {
            //todo: add bridge.
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::PRICE_QUERY_CONTAINER] = function (Container $container) {
            //todo: add bridge.
            return $container->getLocator()->price()->queryContainer();
        };

        $container[self::STOCK_QUERY_CONTAINER] = function (Container $container) {
            //todo: add bridge.
            return $container->getLocator()->stock()->queryContainer();
        };

        return $container;
    }

}
