<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
