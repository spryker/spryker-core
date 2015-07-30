<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ShipmentCheckoutConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class ShipmentCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_SHIPMENT = 'shipment facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_SHIPMENT] = function (Container $container) {
            return $container->getLocator()->shipment()->facade();
        };

        return $container;
    }

}
