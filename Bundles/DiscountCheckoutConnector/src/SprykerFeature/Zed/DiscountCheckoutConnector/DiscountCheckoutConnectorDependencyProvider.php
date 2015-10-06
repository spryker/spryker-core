<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\DiscountCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class DiscountCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_DISCOUNT = 'discount query container';
    const FACADE_DISCOUNT = 'facade discount';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_DISCOUNT] = function (Container $container) {
            return $container->getLocator()->discount()->queryContainer();
        };

        $container[self::FACADE_DISCOUNT] = function (Container $container) {
            return $container->getLocator()->discount()->facade();
        };

        return $container;
    }

}
