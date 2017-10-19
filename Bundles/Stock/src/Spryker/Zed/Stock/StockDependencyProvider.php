<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductBridge;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchBridge;

class StockDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_TOUCH = 'facade touch';
    const FACADE_PRODUCT = 'facade product';
    const PLUGINS_STOCK_UPDATE = 'stock update plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new StockToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new StockToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::PLUGINS_STOCK_UPDATE] = function (Container $container) {
            return $this->getStockUpdateHandlerPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface[]
     */
    protected function getStockUpdateHandlerPlugins($container)
    {
        return [];
    }
}
