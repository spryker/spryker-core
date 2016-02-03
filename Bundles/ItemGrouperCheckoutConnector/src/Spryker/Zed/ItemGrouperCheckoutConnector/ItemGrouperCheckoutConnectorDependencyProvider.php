<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector;

use Spryker\Zed\ItemGrouperCheckoutConnector\Dependency\Facade\ItemGrouperCheckoutConnectorToItemGrouperBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ItemGrouperCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_ITEM_GROUPER = 'item_grouper_facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_ITEM_GROUPER] = function (Container $container) {
            return new ItemGrouperCheckoutConnectorToItemGrouperBridge($container->getLocator()->itemGrouper()->facade());
        };

        return $container;
    }

}
