<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCartBridge;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToCustomerBridge;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToProductBridge;
use Spryker\Client\Wishlist\Dependency\Client\WishlistToStorageBridge;

class WishlistDependencyProvider extends AbstractDependencyProvider
{

    const SERVICE_ZED = 'SERVICE_ZED';
    const CLIENT_CART = 'CLIENT_CART';
    const CLIENT_PRODUCT = 'CLIENT_PRODUCT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container[self::CLIENT_PRODUCT] = function (Container $container) {
            return new WishlistToProductBridge($container->getLocator()->product()->client());
        };

        $container[self::CLIENT_CART] = function (Container $container) {
            return new WishlistToCartBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

}
