<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceDataFeed;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceDataFeed\Dependency\QueryContainer\PriceDataFeedToPriceBridge;

class PriceDataFeedDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRICE_PRODUCT_QUERY_CONTAINER = 'PRICE_QUERY_CONTAINER';

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
        $container[self::PRICE_PRODUCT_QUERY_CONTAINER] = function (Container $container) {
            $priceQueryContainer = $container->getLocator()
                ->priceProduct()
                ->queryContainer();

            return new PriceDataFeedToPriceBridge($priceQueryContainer);
        };

        return $container;
    }
}
