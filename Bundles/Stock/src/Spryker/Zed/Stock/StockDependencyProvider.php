<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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

    /**
     * @param Container $container
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

        return $container;
    }

}
