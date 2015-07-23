<?php

namespace SprykerFeature\Client\Wishlist;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class WishlistDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_ZED = "service_zed";

    const SESSION = "session";

    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container[self::SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

    }
}
