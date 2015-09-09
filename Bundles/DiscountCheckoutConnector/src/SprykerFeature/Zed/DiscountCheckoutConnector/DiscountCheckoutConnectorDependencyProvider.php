<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class DiscountCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_DISCOUNT = 'discount facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return $container->getLocator()->discount()->facade();
        };

        return $container;
    }

}
