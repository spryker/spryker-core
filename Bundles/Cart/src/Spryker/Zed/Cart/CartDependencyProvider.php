<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CALCULATION = 'calculation facade';
    const FACADE_ITEM_GROUPER = 'item grouper facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CALCULATION] = function (Container $container) {
            return new CartToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        $container[self::FACADE_ITEM_GROUPER] = function (Container $container) {
            return new CartToItemGrouperBridge($container->getLocator()->itemGrouper()->facade());
        };

        return $container;
    }

}
