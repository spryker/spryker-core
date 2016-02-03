<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector;

use Spryker\Zed\ItemGrouperWishlistConnector\Dependency\Facade\ItemGrouperWishlistConnectorToItemGrouperBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ItemGrouperWishlistConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_ITEM_GROUPER = 'facade item grouper';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_ITEM_GROUPER] = function (Container $container) {
            return new ItemGrouperWishlistConnectorToItemGrouperBridge($container->getLocator()->itemGrouper()->facade());
        };

        return $container;
    }

}
