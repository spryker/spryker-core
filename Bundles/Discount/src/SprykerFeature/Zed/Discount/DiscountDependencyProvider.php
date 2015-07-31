<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class DiscountDependencyProvider extends AbstractBundleDependencyProvider
{
    const STORE_CONFIG = 'store_config';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::STORE_CONFIG] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }
}
