<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\StockSalesConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StockSalesConnector\Dependency\Facade\StockSalesConnectorToStockBridge;

class StockSalesConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_STOCK = 'stock facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_STOCK] = function (Container $container) {
            return new StockSalesConnectorToStockBridge($container->getLocator()->stock()->facade());
        };

        return $container;
    }

}
