<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class WishlistDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_ZED = 'service_zed';
    const SESSION = 'session';
    const STORAGE = 'store';
    const PRODUCT_CLIENT = 'product_client';
    const CUSTOMER_CLIENT = 'customer_client';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container[self::SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        $container[self::STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[self::PRODUCT_CLIENT] = function (Container $container) {
            return $container->getLocator()->product()->client();
        };

        $container[self::CUSTOMER_CLIENT] = function (Container $container) {
            return $container->getLocator()->customer()->client();
        };

        return $container;
    }
}
