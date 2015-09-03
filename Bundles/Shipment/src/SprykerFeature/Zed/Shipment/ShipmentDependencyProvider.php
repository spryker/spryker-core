<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{

    const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    const PRICE_CALCULATION_PLUGINS = 'PRICE_CALCULATION_PLUGINS';
    const TAX_CALCULATION_PLUGINS = 'TAX_CALCULATION_PLUGINS';
    const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';
    const PLUGINS = 'PLUGINS';

    const QUERY_CONTAINER_TAX = 'QUERY_CONTAINER_TAX';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_TAX] = function (Container $container) {
            return $container->getLocator()->tax()->queryContainer();
        };

        return $container;
    }

}
