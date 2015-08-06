<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperWishlistConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ItemGrouperWishlistConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_ITEM_GROUPER = 'facade item groupper';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_ITEM_GROUPER] = function (Container $container) {
            return $container->getLocator()->itemGrouper()->facade();
        };

        return $container;
    }
}
