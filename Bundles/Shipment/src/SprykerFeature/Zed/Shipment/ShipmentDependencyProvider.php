<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{

    const AVAILABILITY_PLUGINS = 'availability plugins';
    const PRICE_CALCULATION_PLUGINS = 'price calculation plugins';
    const DELIVERY_TIME_PLUGINS = 'delivery time plugins';
    const PLUGINS = 'plugins';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $container;
    }
}
