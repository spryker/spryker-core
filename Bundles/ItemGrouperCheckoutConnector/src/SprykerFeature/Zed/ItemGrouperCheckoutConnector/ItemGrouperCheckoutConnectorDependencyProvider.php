<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ItemGrouperCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_ITEM_GROUPER = 'item_grouper_facade';

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
