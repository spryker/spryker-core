<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class ProductDependencyProvider extends AbstractDependencyProvider
{
    const LOCALE_CLIENT = 'LOCALE_CLIENT';
    const KV_STORAGE = 'KV_STORAGE';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[self::LOCALE_CLIENT] = function (Container $container) {
            return $container->getLocator()->locale()->client();
        };

        return $container;
    }

}
