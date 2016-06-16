<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector;

use Spryker\Zed\DiscountSalesAggregatorConnector\Dependency\Facade\DiscountSalesAggregatorConnectorToTaxBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountSalesAggregatorConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TAX = 'TAX_FACADE';

    const QUERY_CONTAINER_DISCOUNT = 'QUERY_CONTAINER_DISCOUNT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TAX] = function (Container $container) {
            return new DiscountSalesAggregatorConnectorToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::QUERY_CONTAINER_DISCOUNT] = function (Container $container) {
            return $container->getLocator()->discount()->queryContainer();
        };

        return $container;
    }





}
