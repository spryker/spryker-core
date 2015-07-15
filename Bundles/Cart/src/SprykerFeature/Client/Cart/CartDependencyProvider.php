<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class CartDependencyProvider extends AbstractDependencyProvider
{

    const SERVICE_ZED = 'zed service';

    const SESSION = 'session';

    const KV_STORAGE = 'kv storage';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::SESSION] = function (Container $container) {
            return $container->getLocator()->session()->client();
        };

        $container[self::SERVICE_ZED] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        $container[self::KV_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        return $container;
    }

}
