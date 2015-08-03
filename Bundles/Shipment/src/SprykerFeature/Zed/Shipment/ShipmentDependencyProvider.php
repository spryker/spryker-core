<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{

    const AVAILABILITY_PLUGINS = 'availability plugins';
    const PRICE_CALCULATION_PLUGINS = 'price calculation plugins';
    const DELIVERY_TIME_PLUGINS = 'delivery time plugins';
    const PLUGINS = 'plugins';
}
